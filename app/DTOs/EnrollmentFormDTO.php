<?php

namespace App\DTOs;

class EnrollmentFormDTO
{
    public function __construct(
        // Meta (not PDF fields, used for file generation)
        public readonly int    $id,
        public readonly string $first_name,
        public readonly string $last_name,

        // Page 1
        public readonly string $title_region,
        public readonly string $full_name,
        public readonly string $entered_date,
        public readonly string $address,
        public readonly string $employer,
        public readonly string $tshirt_size,                   // matches PDF field name exactly
        public readonly string $phone,
        public readonly string $work_phone,
        public readonly string $other_phone,
        public readonly string $email,
        public readonly string $case_worker_name,
        public readonly string $case_worker_phone,
        public readonly string $monthly_child_support,
        public readonly string $marital_status,
        public readonly string $ethnicity,
        public readonly string $contact_with_children,
        public readonly string $children_custody,
        public readonly string $children_visitation,
        public readonly string $children_phone,

        /** @var ChildDTO[] */
        public readonly array          $children,
        public readonly DisclosureDTO  $disclosure,
        public readonly AssessmentDTO  $assessment,
        public readonly SurveyDTO      $survey,
        public readonly ServicePlanDTO $servicePlan,
    ) {}

    /**
     * Flattens all nested DTOs into a key => value array for pdftk.
     * Keys match PDF field names exactly from getDataFields() output.
     */
    public function toPdfArray(): array
    {
        return [
            // Page 1 - Enrollment
            'title_region'                                      => $this->title_region,
            'full_name'                                         => $this->full_name,
            'entered_date'                                      => $this->entered_date,
            'address'                                           => $this->address,
            'employer'                                          => $this->employer,
            'tshirt_size'                                       => $this->tshirt_size,
            'phone'                                             => $this->phone,
            'work_phone'                                        => $this->work_phone,
            'other_phone'                                       => $this->other_phone,
            'email'                                             => $this->email,
            'case_worker_name'                                  => $this->case_worker_name,
            'case_worker_phone'                                 => $this->case_worker_phone,
            'monthly_child_support'                             => $this->monthly_child_support,
            'marital_status'                                    => $this->marital_status,
            'ethnicity'                                         => $this->ethnicity,
            'contact_with_children'                             => $this->contact_with_children,
            'children_custody'                                  => $this->children_custody,
            'children_visitation'                               => $this->children_visitation,
            'children_phone'                                    => $this->children_phone,

            // Children
            'child_name_1'                                      => $this->children[0]->name ?? '',
            'child_age_1'                                       => $this->children[0]->age ?? '',
            'child_dob_1'                                       => $this->children[0]->dob ?? '',
            'child_name_2'                                      => $this->children[1]->name ?? '',
            'child_age_2'                                       => $this->children[1]->age ?? '',
            'child_dob_2'                                       => $this->children[1]->dob ?? '',
            'child_name_3'                                      => $this->children[2]->name ?? '',
            'child_age_3'                                       => $this->children[2]->age ?? '',
            'child_dob_3'                                       => $this->children[2]->dob ?? '',
            'child_name_4'                                      => $this->children[3]->name ?? '',
            'child_age_4'                                       => $this->children[3]->age ?? '',
            'child_dob_4'                                       => $this->children[3]->dob ?? '',
            'child_name_5'                                      => $this->children[4]->name ?? '',
            'child_age_5'                                       => $this->children[4]->age ?? '',
            'child_dob_5'                                       => $this->children[4]->dob ?? '',

            // Page 2 - Disclosure
            'authorize_full_name'                               => $this->full_name,
            'authorize_dys'                                     => $this->disclosure->authorize_dys,
            'authorize_mhd'                                     => $this->disclosure->authorize_mhd,
            'authorize_dfas'                                    => $this->disclosure->authorize_dfas,
            'authorize_mmac'                                    => $this->disclosure->authorize_mmac,
            'authorize_other'                                   => $this->disclosure->authorize_other,
            'authorize_discloser_form_other'                    => $this->disclosure->authorize_discloser_form_other ?? '',
            'authorize_cd'                                      => $this->disclosure->authorize_cd,
            'authorize_dls'                                     => $this->disclosure->authorize_dls,
            'disclose_full_name'                                => $this->disclosure->full_name,
            'disclose_phone'                                    => $this->disclosure->phone,
            'disclose_dob'                                      => $this->disclosure->dob,
            ### Removed - we should not collect this information
            // 'disclose_ssn'                                      => $this->disclosure->ssn ?? '',
            'disclose_address'                                  => $this->disclosure->address,
            'disclose_email'                                    => $this->disclosure->email,
            // 'select_disclose_to_attorney'                       => $this->disclosure->select_disclose_to_attorney,
            'disclose_to_attorney'                              => $this->disclosure->disclose_to_attorney,
            'disclose_to_legislator'                            => $this->disclosure->disclose_to_legislator,
            'disclose_to_employer'                              => $this->disclosure->disclose_to_employer,
            'disclose_to_governors_staff'                       => $this->disclosure->disclose_to_governors_staff,
            ## Removed - pre-filled
            // 'other_discloser'                                   => $this->disclosure->other_discloser,
            ## Removed - pre-filled
            // 'disclosure_purpose_eligibility_determination'      => $this->disclosure->purpose_eligibility_determination,
            ## Removed - pre-filled
            // 'disclosure_purpose_employment'                     => $this->disclosure->purpose_employment,
            'disclosure_purpose_continuity_of_services_care'   => $this->disclosure->purpose_continuity_of_services_care,
            'disclosure_purpose_legal_consultation_representation' => $this->disclosure->purpose_legal_consultation_representation,
            'disclosure_purpose_complaint_investigation_resolution' => $this->disclosure->purpose_complaint_investigation_resolution,
            'disclosure_purpose_background_investigation'       => $this->disclosure->purpose_background_investigation,
            'disclosure_purpose_legal_proceedings'              => $this->disclosure->purpose_legal_proceedings,
            'disclosure_purpose_treatment_planning'             => $this->disclosure->purpose_treatment_planning,
            'disclosure_purpose_at_consumers_request'           => $this->disclosure->purpose_at_consumers_request,
            'disclosure_purpose_to_share_or_refer'              => $this->disclosure->purpose_to_share_or_refer,
            ## Removed - pre-filled
            // 'disclosure_purpose_other'                          => $this->disclosure->purpose_other,
            'disclosure_licensure_information'                  => $this->disclosure->licensure_information,
            'disclosure_medical'                                => $this->disclosure->disclosure_medical,
            'disclose_hotline_investigations'                   => $this->disclosure->hotline_investigations,
            'disclosure_home_studies'                           => $this->disclosure->home_studies,
            'disclosure_eligibility_determinations'             => $this->disclosure->eligibility_determinations,
            'disclosure_substance_abuse_treatment'              => $this->disclosure->substance_abuse_treatment,
            'disclosure_client_employment_records'              => $this->disclosure->client_employment_records,

            // Page 3
            'accept_text_messages'                              => $this->disclosure->accept_text_messages,

            // Page 4 - Assessment
            'participant_full_name'                             => $this->assessment->full_name,
            'participant_dob'                                   => $this->assessment->dob,
            ## Removed - we should not collect this information
            // 'participant_ssn'                                   => $this->assessment->ssn ?? '',
            'eligibility_missouri_resident'                     => $this->assessment->eligibility_missouri_resident,
            'eligibility_child_under_18'                        => $this->assessment->eligibility_child_under_18,
            'financial_assessment_eligibility'                  => $this->assessment->financial_eligibility,
            'financial_assessment_drivers_licence'              => $this->assessment->financial_drivers_licence,
            'financial_assessment_utility_bill'                 => $this->assessment->financial_utility_bill,
            'financial_assessment_written_employer_statement'   => $this->assessment->financial_written_employer_statement,
            'financial_assessment_ss_benefits_statement'        => $this->assessment->financial_ss_benefits_statement,
            'financial_assessment_no_employment_income'         => $this->assessment->financial_no_employment_income,
            'financial_assessment_unemployment_compensation'    => $this->assessment->financial_unemployment_compensation,
            'financial_assessment_other'                        => $this->assessment->financial_other,
            'financial_assessment_other_description'            => $this->assessment->financial_other_description ?? '',
            'poverty_level_monthly_income'                      => $this->assessment->poverty_monthly_income,
            'poverty_level_number_of_household_members'         => $this->assessment->poverty_household_members,
            'poverty_level_percentage_fpl'                      => $this->assessment->poverty_percentage_fpl,

            // Page 5 - Survey
            'survey_client_dob'                                 => $this->survey->client_dob,
            'survey_delivery_method'                            => $this->survey->delivery_method,
            'survey_why'                                        => $this->survey->why,
            'survey_other_description'                          => $this->survey->why_other,
            'survey_how'                                        => $this->survey->how,
            'survey_how_other_description'                      => $this->survey->how_other,
            'survey_gain'                                       => $this->survey->gain,
            'survey_gain_other_description'                     => $this->survey->gain_other,

            // Page 6 - Service Plan
            'service_plan_participant_full_name'                => $this->servicePlan->participant_full_name,
            'service_plan_client_number'                        => $this->servicePlan->client_number,
            'service_plan_goal'                                 => $this->servicePlan->goal,
            'service_plan_service_identified'                   => $this->servicePlan->service_identified,
            'service_plan_strategies_1'                         => $this->servicePlan->strategies_1,
            'service_plan_person_responsible_1'                 => $this->servicePlan->person_responsible_1,
            'service_plan_timeline_1'                           => $this->servicePlan->timeline_1,
            'service_plan_measure_of_success_1'                 => $this->servicePlan->measure_of_success_1,
            'service_plan_strategies_2'                         => $this->servicePlan->strategies_2,
            'service_plan_person_responsible_2'                 => $this->servicePlan->person_responsible_2,
            'service_plan_timeline_2'                           => $this->servicePlan->timeline_2,
            'service_plan_measure_of_success_2'                 => $this->servicePlan->measure_of_success_2,
            'service_plan_strategies_3'                         => $this->servicePlan->strategies_3,
            'service_plan_person_responsible_3'                 => $this->servicePlan->person_responsible_3,
            'service_plan_timeline_3'                           => $this->servicePlan->timeline_3,
            'service_plan_measure_of_success_3'                 => $this->servicePlan->measure_of_success_3,
        ];
    }
}
?>