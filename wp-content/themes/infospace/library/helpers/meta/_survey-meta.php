<?php

// Surveys
add_filter('cmb2_meta_boxes', 'cmb2_survey_metabox');
function cmb2_survey_metabox()
{
    global $prefix;

    $survey = new_cmb2_box([
        'id'            => $prefix . 'survey_details',
        'title'         => 'Survey details',
        'object_types'  => ['survey'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ]);



 

    $survey->add_field([
        'id'        => $prefix . 'ninja_form_id',
        'name'      => 'Select Ninja Form',
        'desc'      => 'Choose a Ninja Form for this survey',
        'type'      => 'select',
        'options_cb' => function() {
            $forms = array();
            if (function_exists('Ninja_Forms')) {
                $ninja_forms = Ninja_Forms()->form()->get_forms();
                $forms[''] = 'Select a form...';
                foreach ($ninja_forms as $form) {
                    $forms[$form->get_id()] = $form->get_setting('title');
                }
            }
            return $forms;
        }
    ]);

    $survey->add_field([
        'id'   => $prefix . 'survey_active',
        'name' => 'Active',
        'desc' => 'Enable/disable this survey',
        'type' => 'checkbox',
    ]);

     $survey->add_field(array(
        'name'    => __('Available to users attached to', 'hrinfospace'),
        'desc'    => __('Drag a page from the left column to the right column to attach them to this user.<br />You may rearrange the order of the resources in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'survey_attached_resource_pages',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
        'options' => array(
            'show_thumbnails' => true, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => array(
                'posts_per_page' => -1,
                'post_type'      => 'resource_page',
            ), // override the get_posts args
        ),
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ));
}
