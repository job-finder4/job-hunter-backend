<?php

namespace Database\Seeders;


use App\Models\Jobad;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        $skills = array(0 =>
            array(
                'name' => 'Engineering and technical Jobs',
                'children' =>
                    array(
                        0 =>
                            array(
                                'name' => 'Aerospace Engineering',
                            ),
                        1 =>
                            array(
                                'name' => 'Automation Engineering',
                            ),
                        2 =>
                            array(
                                'name' => 'Automotive Engineering',
                            ),
                        3 =>
                            array(
                                'name' => 'Building, Supply, Safety Services Engineering',
                            ),
                        4 =>
                            array(
                                'name' => 'Chemical Engineering',
                            ),
                        5 =>
                            array(
                                'name' => 'Construction Engineering',
                            ),
                        6 =>
                            array(
                                'name' => 'Construction, Visualising',
                            ),
                        7 =>
                            array(
                                'name' => 'Dental Technician',
                            ),
                        8 =>
                            array(
                                'name' => 'Development',
                            ),
                        9 =>
                            array(
                                'name' => 'Electrical Engineering',
                            ),
                        10 =>
                            array(
                                'name' => 'Event Technology',
                            ),
                        11 =>
                            array(
                                'name' => 'Industrial Engineer',
                            ),
                        12 =>
                            array(
                                'name' => 'Management, Team Leader',
                            ),
                        13 =>
                            array(
                                'name' => 'Manufacturing Engineering',
                            ),
                        14 =>
                            array(
                                'name' => 'Materials Science',
                            ),
                        15 =>
                            array(
                                'name' => 'Mechanical Engineering',
                            ),
                        16 =>
                            array(
                                'name' => 'Mechatronic',
                            ),
                        17 =>
                            array(
                                'name' => 'Medical Technology',
                            ),
                        18 =>
                            array(
                                'name' => 'Opticians, Acoustician',
                            ),
                        19 =>
                            array(
                                'name' => 'Plastics Engineering',
                            ),
                        20 =>
                            array(
                                'name' => 'Power Engineering and Environmental Engineering',
                            ),
                        21 =>
                            array(
                                'name' => 'Process Engineering',
                            ),
                        22 =>
                            array(
                                'name' => 'Process Management',
                            ),
                        23 =>
                            array(
                                'name' => 'Project Management',
                            ),
                        24 =>
                            array(
                                'name' => 'Quality Management',
                            ),
                        25 =>
                            array(
                                'name' => 'Sales Engineer',
                            ),
                        26 =>
                            array(
                                'name' => 'Service Technician',
                            ),
                        27 =>
                            array(
                                'name' => 'Technical Documentation',
                            ),
                        28 =>
                            array(
                                'name' => 'Tracer',
                            ),
                    ),
            ),
            1 =>
                array(
                    'name' => 'IT',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Applications Administration',
                                ),
                            1 =>
                                array(
                                    'name' => 'Consulting, Engineering',
                                ),
                            2 =>
                                array(
                                    'name' => 'Database Administration',
                                ),
                            3 =>
                                array(
                                    'name' => 'Data Processing, Datawarehouse, Business Intelligence',
                                ),
                            4 =>
                                array(
                                    'name' => 'Embedded Systems, Firmware Development',
                                ),
                            5 =>
                                array(
                                    'name' => 'Hardware Design',
                                ),
                            6 =>
                                array(
                                    'name' => 'Helpdesk',
                                ),
                            7 =>
                                array(
                                    'name' => 'Information Systems',
                                ),
                            8 =>
                                array(
                                    'name' => 'Management, Team Leader',
                                ),
                            9 =>
                                array(
                                    'name' => 'Process Management',
                                ),
                            10 =>
                                array(
                                    'name' => 'Project Management',
                                ),
                            11 =>
                                array(
                                    'name' => 'Quality Assurance, Inspection',
                                ),
                            12 =>
                                array(
                                    'name' => 'SAP / ERP Consulting, Development',
                                ),
                            13 =>
                                array(
                                    'name' => 'Security',
                                ),
                            14 =>
                                array(
                                    'name' => 'Software Development',
                                ),
                            15 =>
                                array(
                                    'name' => 'System and Network Administration',
                                ),
                            16 =>
                                array(
                                    'name' => 'Technical Documentation',
                                ),
                            17 =>
                                array(
                                    'name' => 'Training',
                                ),
                            18 =>
                                array(
                                    'name' => 'Web Development',
                                ),
                        ),
                ),
            2 =>
                array(
                    'name' => 'Sales',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Customer Service',
                                ),
                            1 =>
                                array(
                                    'name' => 'Estate Agent',
                                ),
                            2 =>
                                array(
                                    'name' => 'Field Service',
                                ),
                            3 =>
                                array(
                                    'name' => 'Inside Sales, Sales Clerks',
                                ),
                            4 =>
                                array(
                                    'name' => 'Management, Team Leader',
                                ),
                            5 =>
                                array(
                                    'name' => 'Medical Representative',
                                ),
                            6 =>
                                array(
                                    'name' => 'Pre-Sales',
                                ),
                            7 =>
                                array(
                                    'name' => 'Sales Assistant',
                                ),
                            8 =>
                                array(
                                    'name' => 'Shop Sales (Trade)',
                                ),
                            9 =>
                                array(
                                    'name' => 'Telesales',
                                ),
                        ),
                ),
            3 =>
                array(
                    'name' => 'Marketing and Communication',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'CRM, Direct Marketing',
                                ),
                            1 =>
                                array(
                                    'name' => 'Distribution Marketing',
                                ),
                            2 =>
                                array(
                                    'name' => 'Event Marketing',
                                ),
                            3 =>
                                array(
                                    'name' => 'Journalism, Writing',
                                ),
                            4 =>
                                array(
                                    'name' => 'Management, Team Leader',
                                ),
                            5 =>
                                array(
                                    'name' => 'Market Research, Analysis',
                                ),
                            6 =>
                                array(
                                    'name' => 'Marketing Assistant',
                                ),
                            7 =>
                                array(
                                    'name' => 'Marketing Manager',
                                ),
                            8 =>
                                array(
                                    'name' => 'Media Planning, Purchasing',
                                ),
                            9 =>
                                array(
                                    'name' => 'Online Marketing',
                                ),
                            10 =>
                                array(
                                    'name' => 'Product Management',
                                ),
                            11 =>
                                array(
                                    'name' => 'Public Relations, Corporate Communication',
                                ),
                            12 =>
                                array(
                                    'name' => 'Strategic Marketing',
                                ),
                            13 =>
                                array(
                                    'name' => 'Trade Marketing',
                                ),
                        ),
                ),
            4 =>
                array(
                    'name' => 'Finance',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Accounts Payable',
                                ),
                            1 =>
                                array(
                                    'name' => 'Accounts Receivable',
                                ),
                            2 =>
                                array(
                                    'name' => 'Assets Accountant',
                                ),
                            3 =>
                                array(
                                    'name' => 'Auditor',
                                ),
                            4 =>
                                array(
                                    'name' => 'Business Analyst',
                                ),
                            5 =>
                                array(
                                    'name' => 'Compliance, Security',
                                ),
                            6 =>
                                array(
                                    'name' => 'Controlling',
                                ),
                            7 =>
                                array(
                                    'name' => 'External Auditing',
                                ),
                            8 =>
                                array(
                                    'name' => 'Financial, Chief Accountant',
                                ),
                            9 =>
                                array(
                                    'name' => 'Group accounting',
                                ),
                            10 =>
                                array(
                                    'name' => 'Management, Team Leader',
                                ),
                            11 =>
                                array(
                                    'name' => 'Mergers & Acquisitions',
                                ),
                            12 =>
                                array(
                                    'name' => 'Payroll Accounting',
                                ),
                            13 =>
                                array(
                                    'name' => 'Process Management',
                                ),
                            14 =>
                                array(
                                    'name' => 'Tax Advisor',
                                ),
                            15 =>
                                array(
                                    'name' => 'Treasury, Cash Management',
                                ),
                        ),
                ),
            5 =>
                array(
                    'name' => 'Banking, Financial Services and Insurance',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Analyst',
                                ),
                            1 =>
                                array(
                                    'name' => 'Asset, Fonds Management',
                                ),
                            2 =>
                                array(
                                    'name' => 'Clerk, Insurance Specialists',
                                ),
                            3 =>
                                array(
                                    'name' => 'Compliance, Security',
                                ),
                            4 =>
                                array(
                                    'name' => 'Corporate Banking',
                                ),
                            5 =>
                                array(
                                    'name' => 'Credit Analysis',
                                ),
                            6 =>
                                array(
                                    'name' => 'Insurance Mathematics, Product Development, Statistics',
                                ),
                            7 =>
                                array(
                                    'name' => 'Investment Banking',
                                ),
                            8 =>
                                array(
                                    'name' => 'Investment Consultancy',
                                ),
                            9 =>
                                array(
                                    'name' => 'Management, Team Leader',
                                ),
                            10 =>
                                array(
                                    'name' => 'Mergers & Acquisitions',
                                ),
                            11 =>
                                array(
                                    'name' => 'Monetary Transfers',
                                ),
                            12 =>
                                array(
                                    'name' => 'Private Banking',
                                ),
                            13 =>
                                array(
                                    'name' => 'Stock Exchange Trading',
                                ),
                        ),
                ),
            6 =>
                array(
                    'name' => 'Purchasing, Materials Administration and Logistics',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Dispatching',
                                ),
                            1 =>
                                array(
                                    'name' => 'Fleet Management',
                                ),
                            2 =>
                                array(
                                    'name' => 'Management, Team Leader',
                                ),
                            3 =>
                                array(
                                    'name' => 'Manufacturing Logistics',
                                ),
                            4 =>
                                array(
                                    'name' => 'Outbound, Transportation Logistics',
                                ),
                            5 =>
                                array(
                                    'name' => 'Process Management',
                                ),
                            6 =>
                                array(
                                    'name' => 'Procurement Logistics',
                                ),
                            7 =>
                                array(
                                    'name' => 'Purchasing',
                                ),
                            8 =>
                                array(
                                    'name' => 'Supply Chain Management',
                                ),
                            9 =>
                                array(
                                    'name' => 'Warehouse Logistics',
                                ),
                            10 =>
                                array(
                                    'name' => 'Waste Logistics',
                                ),
                        ),
                ),
            7 =>
                array(
                    'name' => 'HR',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Administration',
                                ),
                            1 =>
                                array(
                                    'name' => 'Compensation and Benefits',
                                ),
                            2 =>
                                array(
                                    'name' => 'Consulting',
                                ),
                            3 =>
                                array(
                                    'name' => 'Management, Team Leader',
                                ),
                            4 =>
                                array(
                                    'name' => 'Payroll',
                                ),
                            5 =>
                                array(
                                    'name' => 'Personnel Specialist',
                                ),
                            6 =>
                                array(
                                    'name' => 'Recruitment and Selection',
                                ),
                            7 =>
                                array(
                                    'name' => 'Training, Development',
                                ),
                        ),
                ),
            8 =>
                array(
                    'name' => 'Administration and Secretariat',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Assistance, Secretarial',
                                ),
                            1 =>
                                array(
                                    'name' => 'Clerk',
                                ),
                            2 =>
                                array(
                                    'name' => 'Office Management',
                                ),
                            3 =>
                                array(
                                    'name' => 'Property Management',
                                ),
                        ),
                ),
            9 =>
                array(
                    'name' => 'Sciences and Research',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Bio Technology',
                                ),
                            1 =>
                                array(
                                    'name' => 'Biology',
                                ),
                            2 =>
                                array(
                                    'name' => 'Chemistry',
                                ),
                            3 =>
                                array(
                                    'name' => 'Geosciences',
                                ),
                            4 =>
                                array(
                                    'name' => 'Mathematics',
                                ),
                            5 =>
                                array(
                                    'name' => 'Pharmaceutic',
                                ),
                            6 =>
                                array(
                                    'name' => 'Physics',
                                ),
                            7 =>
                                array(
                                    'name' => 'Research, Laboratory',
                                ),
                        ),
                ),
            10 =>
                array(
                    'name' => 'Physicians',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Anaesthesiology',
                                ),
                            1 =>
                                array(
                                    'name' => 'Child and Adolescent Psychiatry',
                                ),
                            2 =>
                                array(
                                    'name' => 'Clinical Research',
                                ),
                            3 =>
                                array(
                                    'name' => 'Dentistry',
                                ),
                            4 =>
                                array(
                                    'name' => 'Ear, Nose, Throat Specialist',
                                ),
                            5 =>
                                array(
                                    'name' => 'General Medicine',
                                ),
                            6 =>
                                array(
                                    'name' => 'Gynaecology',
                                ),
                            7 =>
                                array(
                                    'name' => 'Hygiene Medicine, Environmental Medicine',
                                ),
                            8 =>
                                array(
                                    'name' => 'Internal Medicine',
                                ),
                            9 =>
                                array(
                                    'name' => 'Neurosurgery, Neurology',
                                ),
                            10 =>
                                array(
                                    'name' => 'Occupational Medicine',
                                ),
                            11 =>
                                array(
                                    'name' => 'Oncology',
                                ),
                            12 =>
                                array(
                                    'name' => 'Ophthalmology',
                                ),
                            13 =>
                                array(
                                    'name' => 'Oral, Facial Surgery',
                                ),
                            14 =>
                                array(
                                    'name' => 'Orthopaedics',
                                ),
                            15 =>
                                array(
                                    'name' => 'Pathology',
                                ),
                            16 =>
                                array(
                                    'name' => 'Pediatrics',
                                ),
                            17 =>
                                array(
                                    'name' => 'Psychiatry, Psychotherapy',
                                ),
                            18 =>
                                array(
                                    'name' => 'Radiology',
                                ),
                            19 =>
                                array(
                                    'name' => 'Specialist for Dermatological, Venereal Diseases',
                                ),
                            20 =>
                                array(
                                    'name' => 'Surgery',
                                ),
                            21 =>
                                array(
                                    'name' => 'Urology',
                                ),
                            22 =>
                                array(
                                    'name' => 'Veterinary Medicine',
                                ),
                        ),
                ),
            11 =>
                array(
                    'name' => 'Nursing, Therapy',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Anaesthetic, Intensive Care',
                                ),
                            1 =>
                                array(
                                    'name' => 'Dietician',
                                ),
                            2 =>
                                array(
                                    'name' => 'Disability Support Worker',
                                ),
                            3 =>
                                array(
                                    'name' => 'Elderly Care Nurse',
                                ),
                            4 =>
                                array(
                                    'name' => 'Logopaedics',
                                ),
                            5 =>
                                array(
                                    'name' => 'Management, Team Leader',
                                ),
                            6 =>
                                array(
                                    'name' => 'Medical, Ambulant Service',
                                ),
                            7 =>
                                array(
                                    'name' => 'Midwifery',
                                ),
                            8 =>
                                array(
                                    'name' => 'Nursing',
                                ),
                            9 =>
                                array(
                                    'name' => 'Nursing Assistant',
                                ),
                            10 =>
                                array(
                                    'name' => 'Occupational Therapy',
                                ),
                            11 =>
                                array(
                                    'name' => 'Paediatrics Nursing Care',
                                ),
                            12 =>
                                array(
                                    'name' => 'Physical Therapy',
                                ),
                            13 =>
                                array(
                                    'name' => 'Practice Personnel, Medical Support',
                                ),
                            14 =>
                                array(
                                    'name' => 'Psychotherapy',
                                ),
                            15 =>
                                array(
                                    'name' => 'Radiotherapy',
                                ),
                        ),
                ),
            12 =>
                array(
                    'name' => 'Education and Social Policy',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Educator',
                                ),
                            1 =>
                                array(
                                    'name' => 'High School',
                                ),
                            2 =>
                                array(
                                    'name' => 'Kindergarten, Preschool',
                                ),
                            3 =>
                                array(
                                    'name' => 'Management, Team Leader',
                                ),
                            4 =>
                                array(
                                    'name' => 'Primary School',
                                ),
                            5 =>
                                array(
                                    'name' => 'Social, Community Work',
                                ),
                            6 =>
                                array(
                                    'name' => 'Training Supervisor',
                                ),
                            7 =>
                                array(
                                    'name' => 'Training, Adult Education',
                                ),
                            8 =>
                                array(
                                    'name' => 'University, Colleges',
                                ),
                            9 =>
                                array(
                                    'name' => 'Vocational School',
                                ),
                        ),
                ),
            13 =>
                array(
                    'name' => 'Public Sector',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Armed Forces',
                                ),
                            1 =>
                                array(
                                    'name' => 'Civil Servant Federal Affairs',
                                ),
                            2 =>
                                array(
                                    'name' => 'Civil Servant Foreign Affairs',
                                ),
                            3 =>
                                array(
                                    'name' => 'Fire Brigade',
                                ),
                            4 =>
                                array(
                                    'name' => 'Government Custom and Excise Officers',
                                ),
                            5 =>
                                array(
                                    'name' => 'Local Government Officials',
                                ),
                            6 =>
                                array(
                                    'name' => 'Organizations, Diplomatic Representatives',
                                ),
                            7 =>
                                array(
                                    'name' => 'Police, Prison, Related Work',
                                ),
                        ),
                ),
            14 =>
                array(
                    'name' => 'Law',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Administration of Justice (Judges)',
                                ),
                            1 =>
                                array(
                                    'name' => 'Advocacy',
                                ),
                            2 =>
                                array(
                                    'name' => 'Court, Notary, Legal Assistant',
                                ),
                            3 =>
                                array(
                                    'name' => 'Legal Department',
                                ),
                            4 =>
                                array(
                                    'name' => 'Notary',
                                ),
                        ),
                ),
            15 =>
                array(
                    'name' => 'Design and Architecture',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Architecture',
                                ),
                            1 =>
                                array(
                                    'name' => 'Fashion Design, Jewelry Design',
                                ),
                            2 =>
                                array(
                                    'name' => 'Graphic Arts and Communication Design',
                                ),
                            3 =>
                                array(
                                    'name' => 'Media, Screen and Web Design',
                                ),
                            4 =>
                                array(
                                    'name' => 'Photography, Video',
                                ),
                            5 =>
                                array(
                                    'name' => 'Product Design, Industrial Design',
                                ),
                            6 =>
                                array(
                                    'name' => 'Theater, Playhouse, Music, Dance',
                                ),
                        ),
                ),
            16 =>
                array(
                    'name' => 'Manual Work, Service Industry and Manufacturing',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Beauty, Wellness',
                                ),
                            1 =>
                                array(
                                    'name' => 'Construction Worker',
                                ),
                            2 =>
                                array(
                                    'name' => 'Electrician',
                                ),
                            3 =>
                                array(
                                    'name' => 'Food Manufacturing',
                                ),
                            4 =>
                                array(
                                    'name' => 'Gastronomy, Hotel Industry',
                                ),
                            5 =>
                                array(
                                    'name' => 'Interior Design',
                                ),
                            6 =>
                                array(
                                    'name' => 'Management, Team Leader',
                                ),
                            7 =>
                                array(
                                    'name' => 'Manufacturing, Production',
                                ),
                            8 =>
                                array(
                                    'name' => 'Mechanician',
                                ),
                            9 =>
                                array(
                                    'name' => 'Metal Crafts',
                                ),
                            10 =>
                                array(
                                    'name' => 'Painter, Varnisher',
                                ),
                            11 =>
                                array(
                                    'name' => 'Quality Assurance',
                                ),
                            12 =>
                                array(
                                    'name' => 'Security',
                                ),
                            13 =>
                                array(
                                    'name' => 'Travel, Tourism',
                                ),
                            14 =>
                                array(
                                    'name' => 'Wood Crafts',
                                ),
                        ),
                ),
            17 =>
                array(
                    'name' => 'Senior Management',
                    'children' =>
                        array(
                            0 =>
                                array(
                                    'name' => 'Assistance',
                                ),
                            1 =>
                                array(
                                    'name' => 'Branch Management',
                                ),
                            2 =>
                                array(
                                    'name' => 'Business Consulting',
                                ),
                            3 =>
                                array(
                                    'name' => 'Business Development',
                                ),
                            4 =>
                                array(
                                    'name' => 'Department, Business Unit Management',
                                ),
                            5 =>
                                array(
                                    'name' => 'Directors, Chief Executives',
                                ),
                            6 =>
                                array(
                                    'name' => 'Team Leader',
                                ),
                        ),
                )
        );


//        $skills = [
//            [
//                'name' => 'Books',
//                'children' => [
//                    [
//                        'name' => 'Comic Book',
//                        'children' => [
//                            ['name' => 'Marvel Comic Book'],
//                            ['name' => 'DC Comic Book'],
//                            ['name' => 'Action comics'],
//                        ],
//                    ],
//                    [
//                        'name' => 'Textbooks',
//                        'children' => [
//                            ['name' => 'Business'],
//                            ['name' => 'Finance'],
//                            ['name' => 'Computer Science'],
//                        ],
//                    ],
//                ],
//            ],
//            [
//                'name' => 'Electronics',
//                'children' => [
//                    [
//                        'name' => 'TV',
//                        'children' => [
//                            ['name' => 'LED'],
//                            ['name' => 'Blu-ray'],
//                        ],
//                    ],
//                    [
//                        'name' => 'Mobile',
//                        'children' => [
//                            ['name' => 'Samsung'],
//                            ['name' => 'iPhone'],
//                            ['name' => 'Xiomi'],
//                        ],
//                    ],
//                ],
//            ],
//        ];


        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
