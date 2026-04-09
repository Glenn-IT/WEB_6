<?php



$const_display_component_SEGMENT = isset(getSegment()[2]) ? getSegment()[2] : 'dashboard';

function sidebar($value, $const_display_component_SEGMENT, $parent = false)  {
    $sidebar = ($parent) ?'<ul class="pcoded-item pcoded-left-item">' : '<ul class="pcoded-submenu">';
    $active = "";
    foreach ($value as $key => $value) {
        
        if($key == $const_display_component_SEGMENT) {
            $active = "active";

      
        } else {
            $active = "";

        }
                    
        if(isset($value["child"]) && count($value["child"]) > 0) {
            $sidebar .= '<li class="pcoded-hasmenu '. $active.'">';
                $sidebar .= '<a href="javascript:void(0)" class="waves-effect waves-dark">';
                    $sidebar .= '<span class="pcoded-micon"><i class="ti-'.$value["icon"].'"></i></span>';
                    $sidebar .= '<span class="pcoded-mtext" data-i18n="nav.basic-components.main">'.$value["Title"].'</span>';
                    $sidebar .= '<span class="pcoded-mcaret"></span>';
                $sidebar .= '</a>';
                
                $sidebar .= sidebar($value["child"], $const_display_component_SEGMENT, false);


            $sidebar .= '</li>';
        } else {
            $sidebar .= '<li class="'. $active .'">';
                $sidebar .= '<a href="' . baseUrl('/component/'.$key.'/index') . '" class="waves-effect waves-dark">';
                    $sidebar .= '<span class="pcoded-micon"><i class="ti-'.$value["icon"].'"></i><b>D</b></span>';
                    $sidebar .= '<span class="pcoded-mtext" data-i18n="nav.dash.main">'.$value["Title"].'</span>';
                    $sidebar .= '<span class="pcoded-mcaret"></span>';
                $sidebar .= '</a>';
            $sidebar .= '</li>';
        }

        
      
    }
    $sidebar .= '</ul>';
    return $sidebar;
}

$componentPages = sideBarDetails();
$sidebar = '';
foreach ($componentPages as $key => $value) {
    $sidebar .= '<div class="pcoded-navigation-label" data-i18n="nav.category.navigation">'.$key.'</div>';
    $sidebar .=  sidebar($value, $const_display_component_SEGMENT, true);


}

?>



<nav class="pcoded-navbar">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">
        <div class="">
            <div class="main-menu-header">
                <img class="img-80 img-radius" src="<?=$_ENV['URL_HOST']?>public/admin_template/assets/images/avatar-blank.jpg" alt="User-Profile-Image">
                <div class="user-details">
                    <span id="more-details"><?=$_SESSION['username']?></span>
                </div>
            </div>

            <!-- <div class="main-menu-content">
                <ul>
                    <li class="more-details">
                        <a href="user-profile.html"><i class="ti-user"></i>View Profile</a>
                        <a href="#!"><i class="ti-settings"></i>Settings</a>
                        <a href="<?=$_ENV['URL_HOST']?>userLogout"><i class="ti-layout-sidebar-left"></i>Logout</a>
                    </li>
                </ul>
            </div> -->
        </div>
                
        <?php

        echo $sidebar;
        ?>


    
    </div>
</nav>
