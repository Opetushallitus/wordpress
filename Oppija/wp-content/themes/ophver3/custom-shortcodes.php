<?php


function os_raises() {
    
    global $post; 
    $post_id = $post->ID; 
    
    $b = have_rows('os-raise');
    
    
    $output = '';
    
    if( have_rows('os-raise') ) : 

    $output .= '<div class="os-wrapper">';
    
        while ( have_rows('os-raise') ) : the_row();
    
            $title = get_sub_field('os-raise-title');
            $content = get_sub_field('os-raise-content');
            $img = get_sub_field('os-raise-img');
            $button_txt = get_sub_field('os-raise-button');
            $button_link = get_sub_field('os-raise-button-link');
    
        
            $output .= '
                    <h4>' . $title . '</h4>
                    <div class="os-box vertical-align col-sm-16 col-md-16 col-lg-16">
                        
                        
                            <div class="os-box-img col-sm-8 col-md-8 col-lg-8">
                                <img src="' . $img['url'] . '" />
                            </div>

                    
                            <div class="os-box-content col-sm-8 col-md-8 col-lg-8">'
                                . $content .
                            '<a class="btn btn-secondary" href="' . $button_link . '">' . $button_txt . '</a>
                
                            </div>
                            
                    </div>';

        endwhile;
    
    $output .= '</div>';
    
    endif; 
    
       

    return $output;
}