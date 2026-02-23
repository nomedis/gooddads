<?php

namespace App\DTOs;

class AssessmentDTO
{
    public function __construct(
        public readonly string $full_name,
        public readonly string $dob,
        ## Removed - we should not collect this information
        // public readonly ?string $ssn,                          
        public readonly string $eligibility_missouri_resident,
        public readonly string $eligibility_child_under_18,
        public readonly string $financial_eligibility,         
        public readonly string $financial_drivers_licence,
        public readonly string $financial_utility_bill,
        public readonly string $financial_written_employer_statement,
        public readonly string $financial_ss_benefits_statement,
        public readonly string $financial_no_employment_income,
        public readonly string $financial_unemployment_compensation,
        public readonly string $financial_other,
        public readonly ?string $financial_other_description,
        public readonly string $poverty_monthly_income,
        public readonly string $poverty_household_members,
        public readonly string $poverty_percentage_fpl,        
    ) {}
}
?>
