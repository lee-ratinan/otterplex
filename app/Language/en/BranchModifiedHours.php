<?php
return [
    'field' => [
        'id'                    => 'ID',
        'branch_id'             => 'Branch',
        'modified_hours_date'   => 'Affected Date',
        'modified_reason'       => 'Reason',
        'modified_type'         => 'Type',
        'updated_opening_hours' => 'Opening',
        'updated_closing_hours' => 'Closing',
    ],
    'enum'  => [
        'modified_type' => [
            'MODIFIED_HOURS' => 'Modified Hours',
            'CLOSED'         => 'Closed'
        ]
    ]
];