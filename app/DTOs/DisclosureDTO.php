<?php

namespace App\DTOs;

class DisclosureDTO
{
    public function __construct(
        public readonly string $full_name,
        public readonly string $phone,
        public readonly string $dob,
        ## Removed - we should not collect this information
        // public readonly ?string $ssn,
        public readonly string $address,
        public readonly string $email,

        // Divisions
        public readonly string $authorize_dys,
        public readonly string $authorize_mhd,
        public readonly string $authorize_dfas,
        public readonly string $authorize_mmac,
        public readonly string $authorize_other,
        public readonly ?string $authorize_discloser_form_other,
        public readonly string $authorize_cd,
        public readonly string $authorize_dls,

        // Release to
        public readonly string $disclose_to_attorney,          // Text field
        public readonly string $disclose_to_legislator,
        public readonly string $disclose_to_employer,
        public readonly string $disclose_to_governors_staff,
        ## Removed - pre-filled
        // public readonly string $other_discloser,

        // Purpose
        ## Removed - pre-filled
        // public readonly string $purpose_eligibility_determination,
        ## Removed - pre-filled
        // public readonly string $purpose_employment,
        public readonly string $purpose_continuity_of_services_care,
        public readonly string $purpose_legal_consultation_representation,
        public readonly string $purpose_complaint_investigation_resolution, 
        public readonly string $purpose_background_investigation,
        public readonly string $purpose_legal_proceedings,
        public readonly string $purpose_treatment_planning,
        public readonly string $purpose_at_consumers_request,
        public readonly string $purpose_to_share_or_refer,
        public readonly string $purpose_other,

        // To be disclosed
        public readonly string $licensure_information,
        public readonly string $disclosure_medical,             
        public readonly string $hotline_investigations,
        public readonly string $home_studies,
        public readonly string $eligibility_determinations,
        public readonly string $substance_abuse_treatment,
        public readonly string $client_employment_records,

        public readonly string $accept_text_messages,
    ) {}
}
 ?>