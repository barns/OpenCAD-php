<?php

/**
Open source CAD system for RolePlaying Communities.
Copyright (C) 2017 Shane Gill

This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

This program comes with ABSOLUTELY NO WARRANTY; Use at your own risk.
**/
    session_start();

    require("./oc-config.php");

    include("./actions/civActions.php");

	include("./actions/api.php");


    // TODO: Verify user has permission to be on this page

    if (empty($_SESSION['logged_in']))
    {
        header('Location: ./index.php');
        die("Not logged in");
    }
    else
    {
      $name = $_SESSION['name'];
      $uid = $_SESSION['id'];
    }

    $civName = $civDob = $civAddr = "";

    $good911 = "";
    if(isset($_SESSION['good911']))
    {
        $good911 = $_SESSION['good911'];
        unset($_SESSION['good911']);
    }

    $identityMessage = "";
    if(isset($_SESSION['identityMessage']))
    {
        $identityMessage = $_SESSION['identityMessage'];
        unset($_SESSION['identityMessage']);
    }

    $plateMessage = "";
    if(isset($_SESSION['plateMessage']))
    {
        $plateMessage = $_SESSION['plateMessage'];
        unset($_SESSION['plateMessage']);
    }

    $nameMessage = "";
    if(isset($_SESSION['nameMessage']))
    {
        $nameMessage = $_SESSION['nameMessage'];
        unset($_SESSION['nameMessage']);
    }
    $weaponMessage = "";
    if(isset($_SESSION['weaponMessage']))
    {
        $weaponMessage = $_SESSION['weaponMessage'];
        unset($_SESSION['weaponMessage']);
    }

?>

<!DOCTYPE html>
<html lang="en">
   <?php include "./oc-includes/header.inc.php"; ?>
   <body class="nav-md">
      <div class="container body">
         <div class="main_container">
            <div class="col-md-3 left_col">
               <div class="left_col scroll-view">
                  <div class="navbar nav_title" style="border: 0;">
                     <a href="javascript:void(0)" class="site_title"><i class="fa fa-tachometer"></i> <span><?php echo COMMUNITY_NAME;?> Civilian</span></a>
                  </div>
                  <div class="clearfix"></div>
                  <!-- menu profile quick info -->
                  <div class="profile clearfix">
                     <div class="profile_pic">
                        <img src="<?php echo get_avatar() ?>" alt="..." class="img-circle profile_img">
                     </div>
                     <div class="profile_info">
                        <span>Welcome,</span>
                        <h2><?php echo $name;?></h2>
                     </div>
                     <div class="clearfix"></div>
                  </div>
                  <!-- /menu profile quick info -->
                  <br />
                  <!-- sidebar menu -->
                  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                     <div class="menu_section">
                        <h3>General</h3>
                        <ul class="nav side-menu">
                           <li class="active">
                              <a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                              <ul class="nav child_menu" style="display: block;">
                                 <li class="current-page"><a href="javascript:void(0)">Civilian Dashboard</a></li>
                              </ul>
                           </li>
                           <?php
						   if (CIV_WARRANT === true) { ?> <li>
                              <a><i class="fa fa-warning"></i> Outstanding Warrants <span class="fa fa-chevron-down"></span></a>
                              <ul class="nav child_menu">
                                 <li><a type="button" data-toggle="modal" data-target="#createWarrant" > Create Warrants</a></li>
                                 <li><a type="button" data-toggle="modal" data-target="#viewWarrant" > View Warrants</a></li>
                              </ul>
                           </li>
						    <?php } else { ?>
							<?php }
							?>
                        </ul>
                     </div>
                     <!-- ./ menu_section -->
                  </div>
                  <!-- /sidebar menu -->
                  <!-- /menu footer buttons -->
                  <div class="sidebar-footer hidden-small">
                     <!--
                        —— Left in for user settings. To be introduced later. Probably after RC1. ——
                        <a data-toggle="tooltip" data-placement="top">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>-->
                     <a data-toggle="tooltip" data-placement="top">
                     <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                     </a>
                     <a data-toggle="tooltip" data-placement="top" title="Go to Dashboard" href="<?php echo BASE_URL; ?>/dashboard.php">
                     <span class="glyphicon glyphicon-th" aria-hidden="true"></span>
                     </a>
                     <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo BASE_URL; ?>/actions/logout.php">
                     <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                     </a>
                  </div>
                  <!-- /menu footer buttons -->
               </div>
            </div>
            <!-- top navigation -->
            <div class="top_nav">
               <div class="nav_menu">
                  <nav>
                     <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                     </div>
                     <ul class="nav navbar-nav navbar-right">
                        <li class="">
                           <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                           <img src="<?php echo get_avatar() ?>" alt=""><?php echo $_SESSION['name']; ?>
                           <span class=" fa fa-angle-down"></span>
                           </a>
                           <ul class="dropdown-menu dropdown-usermenu pull-right">
                              <li><a href="<?php echo BASE_URL; ?>/profile.php">My Profile</a></li>
                              <li><a href="<?php echo BASE_URL; ?>/actions/logout.php?responder=<?php echo $_SESSION['identifier'];?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                              <span class="glyphicon glyphicon-log">
                           </ul>
                        </li>
                     </ul>
                  </nav>
               </div>
            </div>
            <!-- /top navigation -->
            <!-- page content -->
            <div class="right_col" role="main">
               <div class="">
                  <div class="page-title">
                     <div class="title_left">
                        <h3>CAD Civilian</h3>
                     </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="row">
                     <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel" id="name_panel">
                           <div class="x_title">
                              <h2>My Identities</h2>
                              <ul class="nav navbar-right panel_toolbox">
                                 <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                 </li>
                                 <li><a class="close-link"><i class="fa fa-close"></i></a>
                                 </li>
                              </ul>
                              <div class="clearfix"></div>
                           </div>
                           <!-- ./ x_title -->
                           <div class="x_content">
							  <?php echo $nameMessage;?>
							  <?php echo $identityMessage;?>
                              <?php ncicGetNames();?>
                           </div>
                           <!-- ./ x_content -->
						   <div class="x_footer">
							<button class="btn btn-primary" name="submitIdentity_btn" type="submit" data-toggle="modal" data-target="#IdentityModal">New Identity</button>
                        </div>
                        <!-- ./ x_panel -->
                     </div>
					</div>
					</div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" id="plate_panel">
                  <div class="x_title">
                    <h2>My Vehicles</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <!-- ./ x_title -->
                  <div class="x_content">
                     <?php echo $plateMessage;?>
                     <?php ncicGetPlates();?>
                  </div>
                  <!-- ./ x_content -->
                  <div class="x_footer">
                    <button class="btn btn-primary" name="create_plate_btn" type="submit" data-toggle="modal" data-target="#createPlateModal">Create Plate</button>
                  </div>
                  <!-- ./ x_footer -->
                </div>
                <!-- ./ x_panel -->
              </div>
              <!-- ./ col-md-12 col-sm-12 col-xs-12 -->
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" id="plate_panel">
                  <div class="x_title">
                    <h2>My Weapons</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <!-- ./ x_title -->
                  <div class="x_content">
                     <?php echo $weaponMessage;?>
                     <?php ncicGetWeapons();?>
                  </div>
                  <!-- ./ x_content -->
                  <div class="x_footer">
                    <button class="btn btn-primary" name="create_weapon_btn" type="submit" data-toggle="modal" data-target="#createWeaponModal">Create Weapon</button>
                  </div>
                  <!-- ./ x_footer -->
                </div>
                <!-- ./ x_panel -->
              </div>
              <!-- ./ col-md-12 col-sm-12 col-xs-12 -->
            </div>
            <!-- ./ row -->
                  <!-- ./ row -->
                     <!-- ./ col-md-6 col-sm-6 col-xs-6 -->
                     <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="x_panel" id="911_panel">
                           <div class="x_title">
                              <h2>New 999 Call</h2>
                              <ul class="nav navbar-right panel_toolbox">
                                 <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                 </li>
                                 <li><a class="close-link"><i class="fa fa-close"></i></a>
                                 </li>
                              </ul>
                              <div class="clearfix"></div>
                           </div>
                           <!-- ./ x_title -->
                           <div class="x_content">
                              <?php echo $good911;?>
                              <form id="new_911" method="post" action="<?php echo BASE_URL; ?>/actions/civActions.php">
                                 <div class="form-group row">
                                    <label class="col-md-2 control-label">Caller Name</label>
                                    <div class="col-md-10">
                                       <input type="text" name="911_caller" class="form-control" id="911_caller" required/>
                                    </div>
                                    <!-- ./ col-sm-9 -->
                                 </div>
                                 <!-- ./ form-group row -->
                                 <div class="form-group row">
                                    <label class="col-md-2 control-label">Location</label>
                                    <div class="col-md-10">
                                       <input type="text" name="911_location" class="form-control" id="911_location" required/>
                                    </div>
                                    <!-- ./ col-sm-9 -->
                                 </div>
                                 <!-- ./ form-group row -->
                                 <div class="form-group row">
                                    <label class="col-md-2 control-label"><span>Description <a data-toggle="modal" href="#911CallHelpModal"><i class="fa fa-question-circle"></i></a></span></label>
                                    <div class="col-md-10">
                                       <textarea id="911_description" name="911_description" class="form-control" style="resize:none;" rows="4"></textarea>
                                    </div>
                                    <!-- ./ col-sm-9 -->
                                 </div>
                                 <!-- ./ form-group row -->
                           </div>
                           <!-- ./ x_content -->
                           <div class="x_footer">
                           <button type="reset" class="btn btn-default" value="Reset">Reset</button>
                           <input type="submit" class="btn btn-primary" name="new_911" value="Submit 911 Call"/>
                           </div>
                           <!-- ./ x_footer -->
                           </form>
                        </div>
                        <!-- ./ x_panel -->
                     </div>
                     <!-- ./ col-md-6 col-sm-6 col-xs-6 -->
                  </div>
                  <!-- ./ row -->
               </div>
               <!-- "" -->
            </div>
            <!-- /page content -->
            <!-- footer content -->
            <footer>
               <div class="pull-right">
                  <?php echo COMMUNITY_NAME;?> Officer Information System | <?php pageLoadTime(); ?>
               </div>
               <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
         </div>
      </div>
      <!-- modals -->
      <!-- Create Warrant Modal -->
      <div class="modal fade" id="createWarrant" tabindex="-1" role="dialog" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" id="closeNewCall"><span aria-hidden="true">×</span>
                  </button>
            <h4 class="modal-title" id="myModalLabel">Warrant Creator</h4>
          </div>
          <!-- ./ modal-header -->
          <div class="modal-body">
            <form role="form" action="<?php echo BASE_URL; ?>/actions/civActions.php" method="post">
                <div class="form-group row">
                <label class="col-lg-2 control-label">Civilian Name</label>
                <div class="col-lg-10">
                  <select class="form-control selectpicker" name="civilian_names" id="civilian_names" data-live-search="true" required>
                    <option> </option>
                    <?php getCivilianNamesOwn();?>
                  </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Warrant Name</label>
                <div class="col-lg-10">
                  <select class="form-control selectpicker" name="warrant_name_sel" id="warrant_name_sel" data-live-search="true" title="Select a Warrant">
                    <optgroup label="Violent Warrants (60 day expiry)">
                      <option value="3rd Degree Murder">Murder</option>
                      <option value="Attempted Murder">Attempted Murder</option>
                      <option value="Kidnapping">Kidnapping</option>
                      <option value="Attempted Kidnapping">Attempted Kidnapping</option>
                      <option value="Hostage Taking">False Imprisonment</option>
                      <option value="Bank/Fed Robbery">Armed Robbery</option>
                      <option value="Terroristic Activity">Terroristic Activity</option>
                      <option value="Terroristic Threats">Terroristic Threats</option>
                      <option value="JailBreak">Escape from Custody</option>
                      <option value="Robbery">Robbery</option>
                      <option value="Grand Theft Auto">Theft of Motor Vehicle</option>
					  <option value="Grand Theft Auto">Taking without Owners Consent</option>
                      <option value="Burglary">Burglary</option>
                      <option value="Threatening an Official">Threatening to Kill</option>
                      <option value="Sexual Assault">Sexual Assault</option>
                      <option value="Hate Crime">Hate Crime</option>
                      <option value="Assault">Grevious Bodily Harm with Intent</option>
                      <option value="Conspiracy">Conspiracy to Supply</option>
                      <option value="Drug Trafficking">Possesion with Intent to Supply</option>
                      <option value="Evasion/Fleeing/Eluding">Failing to Stop for Police</option>
                      <option value="Resisting Arrest">Assault with intent to resist arrest</option>
                      <option value="Firearm in City Limits">Possesion of a Firearm</option>
                      <option value="Firearm by Felon">Sales of Firearms</option>
                      <option value="Unlicensed Firearm">Manufacturing of Firearms</option>
                      <option value="Firearm Discharge in City Limits">Possesion of Firearms with intent to cause fear</option>
                      <option value="Illegal Weapon">Possesion of an Offensive Weapon</option>
                      <option value="Illegal Magazine">Possesion of a Bladed Article</option>
                      <option value="Failure to Inform">Failure to Appear for Bail/Court Indictable</option>
                    </optgroup>
                    <optgroup label="Non-Violent Warrants (30 day expiry)">
                      <option value="FTA: Lewd Conduct">Common Assault</option>
                      <option value="FTA: DUI/DWI">Actual Bodily Harm</option>
                      <option value="FTA: Fraud">Section 5 Public Order</option>
                      <option value="FTA: Hit and Run">Section 4 Public Order</option>
                      <option value="FTA: Speeding">Section 4A Public Order</option>
                      <option value="FTA: Reckless Driving">Failing to stop at an accident</option>
                      <option value="FTA: Obstruction of Justice">Dangerous Driving</option>
                      <option value="FTA: Verbal Abuse">Careless Driving</option>
                      <option value="FTA: Bribery">Driving whilst over the prescribed limit</option>
                      <option value="FTA: Disorderly Conduct">Drunk and Disorderly</option>
                      <option value="FTA: Drug Posession">Breach of the Peace</option>
                      <option value="FTA: Trespassing">Criminal Trespassing</option>
                      <option value="FTA: Excessive Noise">Cannabis Warning</option>
                      <option value="FTA: Failure to Identify">FTA: Failure to Appear Summary</option>
                      <option value="FTA: Stalking">Failure to pay outstanding fines</option>
                      <option value="FTA: Public Intoxication">Driving whilst disqualified</option>
                    </optgroup>
                  </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Issuing Force</label>
                <div class="col-lg-10">
                  <select class="form-control selectpicker" name="issuing_agency" id="issuing_agency" data-live-search="true" required>
                    <option> </option>
                    <?php getAgencies();?>
                  </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->

          </div>
          <!-- ./ modal-body -->
          <div class="modal-footer">
                <input name="create_warrant" type="submit" class="btn btn-primary" value="Create" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>
          </div>
          <!-- ./ modal-footer -->
        </div>
        <!-- ./ modal-content -->
      </div>
      <!-- ./ modal-dialog modal-lg -->
    </div>

      <!-- View Warrant Modal -->
      <div class="modal fade" id="viewWarrant" tabindex="-1" role="dialog" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" id="closeNewCall"><span aria-hidden="true">×</span>
                  </button>
            <h4 class="modal-title" id="myModalLabel">Warrant Viewer</h4>
          </div>
          <!-- ./ modal-header -->
          <div class="modal-body">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" id="citation_panel">
                  <div class="x_title">
                    <h2>Outstanding Warrants</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <!-- ./ x_title -->
                  <div class="x_content">
                     <?php ncic_warrants();?>
                  </div>
                  <!-- ./ x_content -->
                </div>
                <!-- ./ x_panel -->
              </div>
              <!-- ./ form-group -->
          </div>
          <!-- ./ modal-body -->
          <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>
          </div>
          <!-- ./ modal-footer -->
        </div>
        <!-- ./ modal-content -->
      </div>
      <!-- ./ modal-dialog modal-lg -->
    </div>

    <div class="modal fade" id="IdentityModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Create Identity</h4>
          </div>
          <!-- ./ modal-header -->
		  <div class="modal-body">
            <form role="form" action="<?php echo BASE_URL; ?>/actions/civActions.php" method="post">
                <div class="form-group row">
                </div>
                <div class="form-group row">
                <label class="col-lg-2 control-label">Name</label>
                <div class="col-lg-10">
					<input name="civNameReq" class="form-control" id="civNameReq" value="<?php echo $civName;?>" required/>
					<span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Date of Birth</label>
                <div class="col-lg-10">
					<input type="text" name="civDobReq" class="form-control" id="datepicker" maxlength="10" value="<?php echo $civDob;?>" required/>
					<span class="fa fa-calendar form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Address</label>
                <div class="col-lg-10">
					<input type="text" name="civAddressReq" class="form-control" id="civAddressReq" value="<?php echo $civAddr;?>" required/>
					<span class="fa fa-location-arrow form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Sex</label>
                <div class="col-lg-10">
					<select name="civSexReq" class="form-control selectpicker" id="civSexReq" title="Select a sex" data-live-search="true" required>
                    <?php getGenders();?>
					</select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Race</label>
                <div class="col-lg-10">
					<select name="civRaceReq" class="form-control selectpicker" id="civRaceReq" title="Select a race or ethnicity" required>
						<option val="indian">American Indian or Alaskan Native</option>
						<option val="asian">Asian</option>
						<option val="black">Black or African American</option>
						<option val="hispanic">Hispanic</option>
						<option val="hawaiian">Native Hawaiian or Other Pacific Islander</option>
						<option val="white">White</option>
						<option val="white">Unknown</option>
					</select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">License Status</label>
                  <div class="col-lg-10">
                    <select name="civDL" class="form-control selectpicker" id="civDL" title="Select a license status" required>
                <option value="Valid"> Substansive </option>
                <option value="Suspended"> Provisional </option>
                <option value="Expired"> Disqualified </option>
                </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
				<label class="col-lg-2 control-label">Hair Color</label>
				<div class="col-lg-10">
				<select name="civHairReq" class="form-control selectpicker" id="civHairReq" title="Select a hair color" required>
					<option val="bld">Bald</option>
					<option val="blk">Black</option>
					<option val="bln">Blond or Strawberry</option>
					<option val="blu">Blue</option>
					<option val="bro">Brown</option>
					<option val="gry">Gray or Partially Gray</option>
					<option val="grn">Green</option>
					<option val="ong">Orange</option>
					<option val="pnk">Pink</option>
					<option val="ple">Purple</option>
					<option val="red">Red or Auburn</option>
					<option val="sdy">Sandy</option>
					<option val="whi">White</option>
					</select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Build</label>
                <div class="col-lg-10">
					<select name="civBuildReq" class="form-control selectpicker" id="civBuildReq" title="Select a build" required>
						<option val="Average">Average</option>
						<option val="Fit">Fit</option>
						<option val="Muscular">Muscular</option>
						<option val="Overweight">Overweight</option>
						<option val="Skinny">Skinny</option>
						<option val="Thin">Thin</option>
						</select>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->

          </div>
              <div class="form-group row">
                <label class="col-lg-2 control-label">Weapon Markers</label>
                <div class="col-lg-10">
					<select name="civWepStat" class="form-control selectpicker" id="civWepStat" title="Select a status" required>
						<option val="Obtained">No</option>
						<option val="Unobtained">Yes</option>
						</select>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->

          </div>
              <div class="form-group row">
                <label class="col-lg-2 control-label">Deceased</label>
                <div class="col-lg-10">
					<select name="civDec" class="form-control selectpicker" id="civDec" title="Are you deceased?" required>
						<option val="Yes">Yes</option>
						<option val="No">No</option>
						</select>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->

          </div>
          <!-- ./ modal-body -->
          <div class="modal-footer">
                <input name="create_name" type="submit" class="btn btn-primary" value="Create" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>
          </div>
          <!-- ./ modal-footer -->
        </div>
        <!-- ./ modal-content -->
      </div>
      <!-- ./ modal-dialog modal-lg -->
    </div>
	</div>
  <!--Edit modal -->
  <div class="modal fade" id="IdentityEditModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Edit Identity</h4>
          </div>
          <!-- ./ modal-header -->
      <div class="modal-body">
            <form role="form" action="<?php echo BASE_URL; ?>/actions/civActions.php" class="editname_modalform" method="post">
                <div class="form-group row">
                </div>
                <div class="form-group row">
                <label class="col-lg-2 control-label">Name</label>
                <div class="col-lg-10">
          <input name="civNameReq" class="form-control" id="civNameReq" value="<?php echo $civName;?>" required/>
          <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Date of Birth</label>
                <div class="col-lg-10">
          <input type="text" name="civDobReq" class="form-control" id="datepicker2" maxlength="10" value="<?php echo $civDob;?>" required/>
          <span class="fa fa-calendar form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Address</label>
                <div class="col-lg-10">
          <input type="text" name="civAddressReq" class="form-control" id="civAddressReq" value="<?php echo $civAddr;?>" required/>
          <span class="fa fa-location-arrow form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Sex</label>
                <div class="col-lg-10">
          <select name="civSexReq" class="form-control selectpicker selectpicker3" id="civSexReq" title="Select a sex" data-live-search="true" required>
                    <?php getGenders();?>
          </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Race</label>
                <div class="col-lg-10">
          <select name="civRaceReq" class="form-control selectpicker civRaceReq_picker" id="civRaceReq" title="Select a race or ethnicity" required>
            <option val="indian">American Indian or Alaskan Native</option>
            <option val="asian">Asian</option>
            <option val="black">Black or African American</option>
            <option val="hispanic">Hispanic</option>
            <option val="hawaiian">Native Hawaiian or Other Pacific Islander</option>
            <option val="white">White</option>
          </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">License Status</label>
                  <div class="col-lg-10">
                    <select name="civDL" class="form-control selectpicker civDL_picker" id="civDL" title="Select a license status" required>
                <option value="Valid"> Substansive </option>
                <option value="Suspended"> Provisional </option>
                <option value="Expired"> Disqualified </option>
                </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
        <label class="col-lg-2 control-label">Hair Color</label>
        <div class="col-lg-10">
        <select name="civHairReq" class="form-control selectpicker civHairReq_picker" id="civHairReq" title="Select a hair color" required>
          <option val="bld">Bald</option>
          <option val="blk">Black</option>
          <option val="bln">Blond or Strawberry</option>
          <option val="blu">Blue</option>
          <option val="bro">Brown</option>
          <option val="gry">Gray or Partially Gray</option>
          <option val="grn">Green</option>
          <option val="ong">Orange</option>
          <option val="pnk">Pink</option>
          <option val="ple">Purple</option>
          <option val="red">Red or Auburn</option>
          <option val="sdy">Sandy</option>
          <option val="whi">White</option>
		  <option val="white">Unknown</option>
          </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Build</label>
                <div class="col-lg-10">
          <select name="civBuildReq" class="form-control selectpicker civBuildReq_picker" id="civBuildReq" title="Select a build" required>
            <option val="Average">Average</option>
            <option val="Fit">Fit</option>
            <option val="Muscular">Muscular</option>
            <option val="Overweight">Overweight</option>
            <option val="Skinny">Skinny</option>
            <option val="Thin">Thin</option>
            </select>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->

          </div>
              <div class="form-group row">
                <label class="col-lg-2 control-label">Weapon Markers</label>
                <div class="col-lg-10">
					<select name="civWepStat" class="form-control civWepStat_picker" id="civWepStat" title="Select a status" required>
						<option val="Obtained">No</option>
						<option val="Unobtained">Yes</option>
						</select>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->

          </div>
              <div class="form-group row">
                <label class="col-lg-2 control-label">Deceased</label>
                <div class="col-lg-10">
					<select name="civDec" class="form-control civDec_picker" id="civDec" title="Are you deceased?" required>
						<option val="Yes">Yes</option>
						<option val="No">No</option>
						</select>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->

          </div>
          <!-- ./ modal-body -->
          <div class="modal-footer">
                <input type="hidden" name="Edit_id" value="" class="Editdataid"/>
                <input name="edit_name" type="submit" class="btn btn-primary" value="Edit" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>
          </div>
          <!-- ./ modal-footer -->
        </div>
        <!-- ./ modal-content -->
      </div>
      <!-- ./ modal-dialog modal-lg -->
    </div>
  </div>
    <!-- ./ modal fade bs-example-modal-lg -->

    <div class="modal fade" id="createPlateModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Add Plate to Database</h4>
          </div>
          <!-- ./ modal-header -->
		  <div class="modal-body">
            <form role="form" action="<?php echo BASE_URL; ?>/actions/civActions.php" method="post">
                <div class="form-group row">
                </div>
                <div class="form-group row">
                <label class="col-lg-2 control-label">Registered Keeper</label>
                <div class="col-lg-10">
                  <select class="form-control selectpicker" name="civilian_names" id="civilian_names" data-live-search="true" required>
                    <option> </option>
                    <?php getCivilianNamesOwn();?>
                  </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">License Plate</label>
                <div class="col-lg-10">
                  <input type="text" class="form-control" name="veh_plate" required/>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Vehicle Make-Model</label>
                <div class="col-lg-10">
                  <select class="form-control selectpicker" name="veh_make_model" id="veh_make_model" data-live-search="true" required>
                    <option> </option>
                    <?php getVehicle();?>
                  </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Vehicle Primary Color</label>
                <div class="col-lg-10">
                  <select class="form-control selectpicker" name="veh_pcolor" data-live-search="true" required>
				  <option val="">  </option>
				  <?php getColors();?>
				  </select>
				  </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Vehicle Secondary Color</label>
                <div class="col-lg-10">
                  <select class="form-control selectpicker" name="veh_scolor" data-live-search="true" required>
				  <option val="">  </option>
				  <?php getColors();?>
				  </select>
				  </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Insurance Status</label>
                <div class="col-lg-10">
                <select class="form-control" name="veh_insurance" required>
                <option value="">  </option>
                <option value="Valid"> Held </option>
                <option value="Expired"> None Showing </option>
                </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Vehicle Markers</label>
                  <div class="col-lg-10">
                    <select class="form-control" name="flags" required>
                <option value="">  </option>
                <option value="None"> None </option>
                <option value="Stolen"> Stolen </option>
                <option value="Wanted"> Drugs </option>
                <option value="Suspended Registration"> Disqualified Driver </option>
				<option value="Wanted"> Weapons </option>
                </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Notes</label>
                <div class="col-lg-10">
                  <input type="text" class="form-control" name="notes" />
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Vehicle's Registered </label>
                <div class="col-lg-10">
                  <select class="form-control" name="veh_reg_state" required>
				  <option value"">  </option>
				  <option value"Alabama"> United Kingdom </option>
				  <option value"Alaska"> EU </option>
				  <option value"Arizona"> Non-EU </option>
				  </select>
				  </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <!-- ./ form-group -->

          </div>
          <!-- ./ modal-body -->
          <div class="modal-footer">
                <input name="create_plate" type="submit" class="btn btn-primary" value="Create" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>
          </div>
          <!-- ./ modal-footer -->
        </div>
        <!-- ./ modal-content -->
      </div>
      <!-- ./ modal-dialog modal-lg -->
    </div>
    <!-- ./ modal fade bs-example-modal-lg -->
    <!-- Edit Plate Modal -->
    <div class="modal fade" id="editPlateModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Edit Plate to Database</h4>
          </div>
          <!-- ./ modal-header -->
      <div class="modal-body">
            <form role="form" action="<?php echo BASE_URL; ?>/actions/civActions.php" method="post">
                <div class="form-group row">
                </div>
                <div class="form-group row">
                <label class="col-lg-2 control-label">Registered Keeper</label>
                <div class="col-lg-10">
                  <select class="form-control selectpicker civilian_names_picker" name="civilian_names" id="civilian_names" data-live-search="true" required>
                    <option> </option>
                    <?php getCivilianNamesOwn();?>
                  </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">License Plate</label>
                <div class="col-lg-10">
                  <input type="text" class="form-control veh_plate" name="veh_plate" required/>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Vehicle Make-Model</label>
                <div class="col-lg-10">
                  <select class="form-control selectpicker veh_makemodelpicker" name="veh_make_model" id="veh_make_model" data-live-search="true" required>
                    <option> </option>
                    <?php getVehicle();?>
                  </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Vehicle Primary Color</label>
                <div class="col-lg-10">
                  <select class="form-control selectpicker veh_pcolor_picker" name="veh_pcolor" data-live-search="true" required>
          <option val="">  </option>
          <?php getColors();?>
          </select>
          </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Vehicle Secondary Color</label>
                <div class="col-lg-10">
                  <select class="form-control selectpicker veh_scolor_picker" name="veh_scolor" data-live-search="true" required>
          <option val="">  </option>
          <?php getColors();?>
          </select>
          </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Insurance Status</label>
                <div class="col-lg-10">
                <select class="form-control veh_insurance_option" name="veh_insurance" id="insurance_edit" required>
                <option value="">  </option>
                <option value="VALID"> Held </option>
                <option value="EXPIRED"> None Showing</option>
                </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Vehicle Flags</label>
                  <div class="col-lg-10">
                    <select class="form-control flags_option" name="flags" required>
                <option value="">  </option>
                <option value="None"> None </option>
                <option value="Stolen"> Stolen </option>
                <option value="Wanted"> Drugs </option>
                <option value="Suspended Registration"> Disqualified Driver </option>
				<option value="Wanted"> Weapons </option>
                </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Notes</label>
                <div class="col-lg-10">
                  <input type="text" class="form-control notes" name="notes" />
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Vehicle Registered</label>
                <div class="col-lg-10">
                  <select class="form-control veh_reg_state_option" name="veh_reg_state" required>
          <option value"">  </option>
				  <option value"Alabama"> United Kingdom </option>
				  <option value"Alaska"> EU </option>
				  <option value"Arizona"> Non-EU </option>
          </select>
          </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <!-- ./ form-group -->

          </div>
          <!-- ./ modal-body -->
          <div class="modal-footer">
                <input type="hidden" class="editplateid" name="Edit_plateId" />
                <input name="edit_plate" type="submit" class="btn btn-primary" value="Edit" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>
          </div>
          <!-- ./ modal-footer -->
        </div>
        <!-- ./ modal-content -->
      </div>
      <!-- ./ modal-dialog modal-lg -->
    </div>
    <!-- ./ modal fade bs-example-modal-lg -->
      <!-- modals -->
    <div class="modal fade" id="createWeaponModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Add Plate to Database</h4>
          </div>
          <!-- ./ modal-header -->
		  <div class="modal-body">
            <form role="form" action="<?php echo BASE_URL; ?>/actions/civActions.php" method="post">
                <div class="form-group row">
                </div>
                <div class="form-group row">
                <label class="col-lg-2 control-label">Registered Keeper</label>
                <div class="col-lg-10">
                  <select class="form-control selectpicker" name="civilian_names" id="civilian_names" data-live-search="true" required>
                    <option> </option>
                    <?php getCivilianNamesOwn();?>
                  </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-lg-2 control-label">Marker Details</label>
                <div class="col-lg-10">
                  <select class="form-control selectpicker" name="weapon_all" id="weapon_all" data-live-search="true" required>
                    <option> </option>
                    <?php getWeapons();?>
                  </select>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
          </div>
          <!-- ./ modal-body -->
          <div class="modal-footer">
                <input name="create_weapon" type="submit" class="btn btn-primary" value="Create" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>
          </div>
          <!-- ./ modal-footer -->
        </div>
        <!-- ./ modal-content -->
      </div>
      <!-- ./ modal-dialog modal-lg -->
    </div>
    <!-- ./ modal fade bs-example-modal-lg -->

      <!-- 911 Call Help Modal -->
      <div class="modal fade" id="911CallHelpModal" tabindex="-1" role="dialog" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">How to Submit a 999 Call</h4>
               </div>
               <!-- ./ modal-header -->
               <div class="modal-body">
                  <span>
                     <p><b>Where, What, Who, When, How, Why if available are the primary things to provide to a 999 dispatcher.</b></p>
                     <p>Some things to consider reporting:</p>
                     <p>
                     <ul>
                        <li>Your name</li>
                        <li>Address responders need to go to</li>
                        <li>Any weapons involved</li>
						<li>Gender of suspect(s) or victim(s)</li>
                        <li>Age of suspect(s) or victim(s)</li>
                        <li>Height and Weight of suspect(s)</li>
                        <li>Clothing description of suspect(s)</li>
                        <li>Drug use (current or past, includes perscription medications) of any victim(s)</li>
                        <li>Any prior violent behavior</li>
                        <li>Any prior information about psychosis, delusions, hallucinations or other mental health considerations</li>
                     </ul>
                     </p>
                  </span>
               </div>
               <!-- ./ modal-body -->
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
               <!-- ./ modal-footer -->
            </div>
            <!-- ./ modal-content -->
         </div>
         <!-- ./ modal-dialog modal-lg -->
      </div>
      <!-- ./ modal fade bs-example-modal-lg -->
      <?php include "./oc-includes/jquery-colsolidated.inc.php"; ?>
      <script>
         $('#civilianDetailsModal').on('show.bs.modal', function(e) {
         var $modal = $(this), civId = e.relatedTarget.id;


         $.ajax({
             cache: false,
             type: 'GET',
             url: './actions/civActions.php',
             data: {'getCivilianDetails': 'yes',
                     'name_id' : civId},
             success: function(result)
             {
                 console.log(result);
                 data = JSON.parse(result);

                 $('input[name="civName"]').val(data['name']);
                 $('input[name="civDob"]').val(data['dob']);
                 $('input[name="civAddress"]').val(data['address']);
                 $('input[name="civSex"]').val(data['sex']);
                 $('input[name="civRace"]').val(data['race']);
                 $('input[name="civHair"]').val(data['hair_color']);
                 $('input[name="civBuild"]').val(data['build']);
                 $('input[name="civPlate"]').val(data['veh_plate']);
                 $('input[name="civMake"]').val(data['veh_make']);
                 $('input[name="civModel"]').val(data['veh_model']);
                 $('input[name="civColor"]').val(data['veh_color']);


             },

             error:function(exception){alert('Exeption:'+exception);}
             });
         });
      </script>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Datepicker - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
  $( function() {
    $( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd'});
  } );
  </script>
  <script>
    $(function(){
      $(document).on('click', '#edit_nameBtn', function(e){
        e.preventDefault();
        var edit_id = $(this).data('id');
        console.log(edit_id);
        $.ajax({
          url: '<?php echo BASE_URL; ?>/actions/civActions.php',
          type: 'POST',
          data: 'editid='+edit_id,
          dataType: 'json',
          cache: false
          })
          .done(function(data){
            $('#IdentityEditModal #civNameReq').val(data.name);
            $('#IdentityEditModal #datepicker2').datepicker({dateFormat: 'yy-mm-dd'}).datepicker('setDate', new Date(data.dob));
            $('#IdentityEditModal #civAddressReq').val(data.address);
            $('.selectpicker3').selectpicker('val', data.gender);
            $('.civRaceReq_picker').selectpicker('val', data.race);
            $('.civDL_picker').selectpicker('val', data.dl_status);
            $('.civHairReq_picker').selectpicker('val', data.hair_color);
            $('.civBuildReq_picker').selectpicker('val', data.build);
			$('.civWepStat_picker').selectpicker('val', data.weapon_permit);
			$('.civDec_picker').selectpicker('val', data.deceased);
            $('#IdentityEditModal .Editdataid').val(data.id);
          });

      })
      /* Edit Plate */
      $(document).on('click', '#edit_plateBtn', function(e){
        e.preventDefault();
        var edit_id = $(this).data('id');
        $.ajax({
          url: '<?php echo BASE_URL; ?>/actions/civActions.php',
          type: 'POST',
          data: 'edit_plateid='+edit_id,
          dataType: 'json',
          cache: false
          })
          .done(function(data){
            $('.civilian_names_picker').selectpicker('val', data.name_id);
            $('.veh_plate').val(data.veh_plate);
            $('.veh_makemodelpicker').selectpicker('val', data.veh_make+' '+data.veh_model);
            $('.veh_pcolor_picker').selectpicker('val', data.veh_pcolor);
            $('.veh_scolor_picker').selectpicker('val', data.veh_scolor);
            $('#insurance_edit').val(data.veh_insurance);
            $('.flags_option').val(data.flags);
            $('.notes').val(data.notes);
            $('.veh_reg_state_option').val(data.veh_reg_state);
            $('.editplateid').val(data.id);
          });
      });
    })
  </script>
   </body>
</html>
