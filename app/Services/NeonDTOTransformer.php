<?php

namespace App\Services;

use App\DTOs\AssessmentDTO;
use App\DTOs\ChildDTO;
use App\DTOs\DisclosureDTO;
use App\DTOs\EnrollmentFormDTO;
use App\DTOs\ServicePlanDTO;
use App\DTOs\SurveyDTO;
use Carbon\Carbon;

class NeonDTOTransformer
{
    public function transformPerson(array $data): EnrollmentFormDTO
    {
        $contactInfo = $data['contactInfo']['records'][0];

        return new EnrollmentFormDTO(
            id:                   $contactInfo['persons_id']['value'],
            first_name:           $contactInfo['firstName']['displayValue'] ?? '',
            last_name:            $contactInfo['lastName']['displayValue'] ?? '',
            title_region:         $contactInfo['regions_id']['displayValue'] ?? '',
            full_name:            $contactInfo['persons_id']['displayValue'] ?? '',
            entered_date:         $this->parseDateString($contactInfo['enteredDate']['value'] ?? null),
            address:              $this->buildAddress($contactInfo),
            employer:             $contactInfo['employer']['value'] ?? '',
            tshirt_size:          $contactInfo['tShirtSize']['displayValue'] ?? '',
            phone:                $contactInfo['homeCellPhone']['value'] ?? '',
            work_phone:           $contactInfo['workPhone']['value'] ?? '',
            other_phone:          $contactInfo['otherNumber']['value'] ?? '',
            email:                $contactInfo['email']['value'] ?? '',
            case_worker_name:     $contactInfo['probationParoleCaseWorkerName']['value'] ?? '',
            case_worker_phone:    $contactInfo['probationParoleCaseWorkerPhone']['value'] ?? '',
            monthly_child_support: $contactInfo['monthlyChildSupportPayment']['displayValue'] ?? '',
            marital_status:       $contactInfo['maritalStatus']['displayValue'] ?? '',
            ethnicity:            $contactInfo['ethnicity']['displayValue'] ?? '',
            contact_with_children: $this->yesNo($contactInfo['contactWithChildren']['displayValue'] ?? null),
            children_custody:     $this->inList($contactInfo['contactType']['value'] ?? '', '763'),
            children_visitation:  $this->inList($contactInfo['contactType']['value'] ?? '', '762'),
            children_phone:       $this->inList($contactInfo['contactType']['value'] ?? '', '1483'),
            children:             $this->transformChildren($data['children']['records'] ?? []),
            disclosure:           $this->transformDisclosure($data['disclosure']['records'][0]),
            assessment:           $this->transformAssessment($data['assessment']['records'][0]),
            survey:               $this->transformSurvey($data['survey']['records'][0]),
            servicePlan:          $this->transformServicePlan($data['servicePlan']['records'][0]),
        );
    }

    private function transformChildren(array $children): array
    {
        $result = [];
        foreach ($children as $child) {
            $dob = $this->parseDate($child['dateOfBirth']['value'] ?? null);
            $result[] = new ChildDTO(
                name: trim(($child['firstName']['value'] ?? '') . ' ' . ($child['lastName']['value'] ?? '')),
                age:  $dob ? (string) $dob->diffInYears(Carbon::now()) : '',
                dob:  $dob ? $dob->format('m/d/Y') : '',
            );
        }
        return $result;
    }

    private function transformDisclosure(array $d): DisclosureDTO
    {
        $divisions = explode(',', $d['division']['value'] ?? '');
        $releaseTo = explode(',', $d['releaseTo']['value'] ?? '');
        $purposes  = explode(',', $d['purposeOfDisclosure']['value'] ?? '');
        $disclosed = explode(',', $d['informationToBeDisclosed']['value'] ?? '');

        return new DisclosureDTO(
            full_name:                                          $d['persons_id']['displayValue'] ?? '',
            phone:                                              $d['homeCellPhone']['value'] ?? '',
            dob:                                                $this->parseDateString($d['dateOfBirth']['value'] ?? null),
            ## We should not collect this information
            // ssn:                                                null,
            address:                                            $d['fullAddress']['displayValue'] ?? '',
            email:                                              $d['email']['value'] ?? '',
            authorize_dys:                                      $this->inArray('679', $divisions),
            authorize_mhd:                                      $this->inArray('684', $divisions),
            authorize_dfas:                                     $this->inArray('683', $divisions),
            authorize_mmac:                                     $this->inArray('1484', $divisions),
            authorize_other:                                    isset($d['divisionOther']['value']) && $d['divisionOther']['value'] ? 'Yes' : 'Off',
            authorize_discloser_form_other:                     $d['divisionOther']['value'] ?? null,
            authorize_cd:                                       $this->inArray('682', $divisions),
            authorize_dls:                                      $this->inArray('681', $divisions),
            ## This is the text field
            // disclose_to_attorney:                               $attorneyInList,
            disclose_to_attorney:                               "Neon has the checkbox value, but not associated text; we have no checkbox field, but the text",
            ## This is the text field
            // disclose_to_legislator:                             $this->inArray('1487', $releaseTo),
            disclose_to_legislator:                             "Neon has the checkbox value, but not associated text; we have no checkbox field, but the text",
            ## This is the text field
            // disclose_to_employer:                               $this->inArray('1486', $releaseTo),
            disclose_to_employer:                               "Neon has the checkbox value, but not associated text; we have no checkbox field, but the text",
            ## This is the text field
            // disclose_to_governors_staff:                        $this->inArray('1488', $releaseTo),
            disclose_to_governors_staff:                        "Neon has the checkbox value, but not associated text; we have no checkbox field, but the text",
            ## Pre-filled
            // other_discloser:                                    $d['releaseToOther']['displayValue'] ?? '',
            ## Pre-filled
            // purpose_eligibility_determination:                  $this->inArray('585', $purposes),
            ## Pre-filled
            // purpose_employment:                                 $this->inArray('594', $purposes),
            purpose_continuity_of_services_care:                $this->inArray('447', $purposes),
            purpose_legal_consultation_representation:          $this->inArray('1490', $purposes),
            purpose_complaint_investigation_resolution:         $this->inArray('1491', $purposes),
            purpose_background_investigation:                   $this->inArray('1492', $purposes),
            purpose_legal_proceedings:                          $this->inArray('1493', $purposes),
            purpose_treatment_planning:                         $this->inArray('1494', $purposes),
            purpose_at_consumers_request:                       $this->inArray('1495', $purposes),
            purpose_to_share_or_refer:                          $this->inArray('755', $purposes),
            //This is the checkbox for the 'other purpose' field which is pre-filled, but the box is not checked, hence 'Yes' here
            purpose_other:                                      "Yes", //$this->inArray('1496', $purposes),
            licensure_information:                              $this->inArray('161', $disclosed),
            disclosure_medical:                                 $this->inArray('1497', $disclosed),
            hotline_investigations:                             $this->inArray('1499', $disclosed),
            home_studies:                                       $this->inArray('1500', $disclosed),
            eligibility_determinations:                         $this->inArray('1501', $disclosed),
            substance_abuse_treatment:                          $this->inArray('1502', $disclosed),
            client_employment_records:                          $this->inArray('1503', $disclosed),
            accept_text_messages:                               $this->yesNo($d['acceptsTextMessage']['displayValue'] ?? null),
        );
    }

    private function transformAssessment(array $a): AssessmentDTO
    {
        $otherValue = $a['other']['displayValue'] ?? null;

        return new AssessmentDTO(
            full_name:                              $a['persons_id']['displayValue'] ?? '',
            dob:                                    $a['dateOfBirth']['displayValue'] ?? '',
            ## We should not collect this information
            // ssn:                                    null,
            eligibility_missouri_resident:          $this->yesNo($a['missouriResident']['displayValue'] ?? null),
            eligibility_child_under_18:             $this->yesNo($a['childUnder18']['displayValue'] ?? null),
            financial_eligibility:                  'Off', // completed by state agency, not in Neon
            financial_drivers_licence:              $this->yesNo($a['dL']['displayValue'] ?? null),
            financial_utility_bill:                 $this->yesNo($a['utilityBill']['displayValue'] ?? null),
            financial_written_employer_statement:   $this->yesNo($a['writtenEmployerStatement']['displayValue'] ?? null),
            financial_ss_benefits_statement:        $this->yesNo($a['socialSecurityBenefitsStatement']['displayValue'] ?? null),
            financial_no_employment_income:         $this->yesNo($a['selfAttestationOfNoEmploymentOrIncome']['displayValue'] ?? null),
            financial_unemployment_compensation:    $this->yesNo($a['unemploymentCompensation']['displayValue'] ?? null),
            financial_other:                        $otherValue ? 'Yes' : 'Off',
            financial_other_description:            $otherValue ?: null,
            poverty_monthly_income:                 $a['hoseholdIncome']['displayValue'] ?? '',  // typo is in Neon field name
            poverty_household_members:              $a['numberOfFamilyMembersInHousehold']['value'] ?? '',
            poverty_percentage_fpl:                 $a['percentageOfFPL']['value'] ?? '',
        );
    }

    private function transformSurvey(array $s): SurveyDTO
    {
        $reasons       = explode(',', $s['reasons']['value'] ?? '');
        $howHeardAbout = explode(',', $s['hearAboutUs']['value'] ?? '');
        $expectedGain  = explode(',', $s['expectToGain']['value'] ?? '');

        return new SurveyDTO(
            client_dob:     $s['dateOfBirth']['displayValue'] ?? '',
            delivery_method: '', // not in Neon — filled in by participant on paper
            why:            match(true) {
                in_array('453',  $reasons) => 'Responsible father',
                in_array('454',  $reasons) => 'Referred',
                in_array('1506', $reasons) => 'Child support concerns',
                in_array('695',  $reasons) => 'Attourney',  // matches PDF FieldStateOption spelling
                in_array('1507', $reasons) => 'Other',
                default => 'Off',
            },
            why_other:      $s['reasonsOther']['value'] ?? '',
            how:            match(true) {
                in_array('1510', $howHeardAbout) => 'Family support',
                in_array('1509', $howHeardAbout) => 'Past participant',
                in_array('1512', $howHeardAbout) => 'Marketing',
                in_array('1511', $howHeardAbout) => 'Prosecuting attorney',
                in_array('1513', $howHeardAbout) => 'The organization',
                in_array('1508', $howHeardAbout) => 'Word of mouth',
                in_array('1514', $howHeardAbout) => 'Other',
                default => 'Off',
            },
            how_other:      $s['hearAboutUsOther']['value'] ?? '',
            gain:           match(true) {
                in_array('1520', $expectedGain) => 'Access to mentors',
                in_array('1524', $expectedGain) => 'Credit repair assistance',
                in_array('1521', $expectedGain) => 'Criminal History Assistance',
                in_array('1522', $expectedGain) => 'Overcoming homelessness assistance',
                in_array('1516', $expectedGain) => 'Abuse assistance',
                in_array('1523', $expectedGain) => 'Visitation custody assistance',
                in_array('1515', $expectedGain) => 'Emplyment opportunities',  // matches PDF FieldStateOption spelling
                in_array('1517', $expectedGain) => 'Parenting skills',
                in_array('1526', $expectedGain) => 'Increased Understanding of Child Support',
                in_array('1525', $expectedGain) => 'Maintaining Hope',
                in_array('1518', $expectedGain) => 'Resume building',
                in_array('1519', $expectedGain) => 'Legal services',
                in_array('1527', $expectedGain) => 'Other',
                default => 'Off',
            },
            gain_other:     $s['expectToGainOther']['value'] ?? '',
        );
    }

    private function transformServicePlan(array $sp): ServicePlanDTO
    {
        return new ServicePlanDTO(
            participant_full_name:   $sp['persons_id']['displayValue'] ?? '',
            client_number:           $sp['clientNumber']['value'] ?? '',
            goal:                    '', // database appears to be missing this field per original comment
            service_identified:      $sp['serviceIdentifiedByTheParticipants']['value'] ?? '',
            strategies_1:            $sp['goals_custodyVisitationObj']['displayValue'] ?? '',
            person_responsible_1:    $sp['goals_custodyVisitationPersonRes']['displayValue'] ?? '',
            timeline_1:              $sp['goals_custodyVisitationTimeline']['displayValue'] ?? '',
            measure_of_success_1:    $sp['goals_custodyVisitationMeasure']['value'] ?? '',
            strategies_2:            $sp['goals_educationEmploymentObj']['displayValue'] ?? '',
            person_responsible_2:    $sp['goals_educationEmploymentPersonRes']['displayValue'] ?? '',
            timeline_2:              $sp['goals_educationEmploymentTimeline']['displayValue'] ?? '',
            measure_of_success_2:    $sp['goals_educationEmploymentMeasure']['value'] ?? '',
            strategies_3:            $sp['goals_housingTransportationObj']['displayValue'] ?? '',
            person_responsible_3:    $sp['goals_housingTransportationPersonRes']['displayValue'] ?? '',
            timeline_3:              $sp['goals_housingTransportationTimeline']['displayValue'] ?? '',
            measure_of_success_3:    $sp['goals_housingTransportationMeasure']['value'] ?? '',
        );
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function parseDate(?string $value): ?Carbon
    {
        return $value ? Carbon::createFromFormat('Y-m-d', $value) : null;
    }

    private function parseDateString(?string $value): string
    {
        if (!$value) return '';
        $date = Carbon::createFromFormat('Y-m-d', $value);
        return $date ? $date->format('m/d/Y') : '';
    }

    private function buildAddress(array $c): string
    {
        return trim(implode(' ', array_filter([
            $c['address1']['value'] ?? '',
            $c['address2']['value'] ?? '',
            $c['city']['value'] ?? '',
            $c['state']['displayValue'] ?? '',
            $c['zip']['value'] ?? '',
        ])));
    }

    private function yesNo(?string $value): string
    {
        return match($value) {
            '1'     => 'Yes',
            '0'     => 'No',
            default => 'Off',
        };
    }

    private function inList(string $list, string $id): string
    {
        return in_array($id, explode(',', $list)) ? 'Yes' : 'Off';
    }

    private function inArray(string $id, array $arr): string
    {
        return in_array($id, $arr) ? 'Yes' : 'Off';
    }
}