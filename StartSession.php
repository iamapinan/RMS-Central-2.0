<?php
include_once('initial.min.php');
include_once('core/class.am.php');

	$idm = new IDM2API;
	$idm->debug = 1;
	$start = $idm->start_session($_GET['sid'], $_GET['Name1']);
if($start->RetCode==1){
	echo '<html>
			<head>
			<meta http-equiv=Content-Type content="text/html; charset=utf-8">
			<title>Start Session</title>
			<style type="text/css">
				*{
					font-family: arial;
				}
				a {
					color: white;
					text-decoration: none;
					background: #666;
					display: block;
					padding: 3px 5px;
					border-radius: 2px;
					font-size: 13px;
					font-family: arial;
				}

			</style>';
			echo file_get_contents(conf('idm_server').'aculearn-idm/api/get_start_session_js.asp');
		?>
</head>
<body  onload="javascript:win_load();">
	<center>
<h2>Start conference</h2>
<table width="100%">
	<tr>
		<td>
			<span id="userIsInConfPrompt" name="userIsInConfPrompt" style="display:none;">
				<center><b>Your ID is being used in another conference</b></center>
			</span>

			<span id="startConfByAuthorPrompt" name="startConfByAuthorPrompt" style="display:none;">
				<center><b>Sorry, the conference session's start mode is host. <br>
				It's started only by author. </b></center>
			</span>

			<span id="webConfOcxPanel" name="webConfOcxPanel">
				<script language="javascript">WebConfOCX();	</script>
			</span>

			<span id="ocxInstallPrompt" name="ocxInstallPrompt" style="display:none;">
				<center><b>AcuCONFERENCE will automatically install a software onto your computer.<br>
				Depending on your system, this process may take a few minutes.<br>
				If you are on Windows XP SP2, you will need to click on the information bar on top of this window and select "Install ActiveX Control".<br>
				Please click on "Install" when you are prompted by Internet Explorer.<br></b></center>
			</span>

			<span id="ocxInvalidFF" name="ocxInvalidFF" style="display:none;">
			<center>The WebConf plugin cannot be used, maybe you disabled it on your browser settings. If not then please try installation again.</center><br>
			</span>

			<span id="ocxInstallPromptFF" name="ocxInstallPromptFF" style="display:none;">
			<center>AcuCONFERENCE needs to install a plugin onto your computer.	<br>
			Please click on "Install Missing Plugins" or <a href='<?php echo $start->Records->Record->OPRClientFF; ?>' target='_blank'>Click Here</a> to download the file.<br>
			You must close all other browser windows before installing.<br>
			After the installation is complete, reload current page and the conference will be started, or you need to restart your browser.</center>
			</span>

			<span id="ocxUpdatePromptFF" name="ocxUpdatePromptFF" style="display:none;">
			<center>Your browser needs to upgrade the WebConf plugins,	<br>
			Please<a href='<?php echo $start->Records->Record->OPRClientFF; ?>' target='_blank'>Click Here</a> to download the file.<br>
			You must close all browser windows before installing.<br>
			After the installation is complete, you need to restart your browser to make the new plugin work.</center>
			</span>

			<span id="conferenceInstallPrompt" name="conferenceInstallPrompt" style="display:none;">
				Please click on "Install now" below. Installation will automatically begin when download is completed.
				<br>
				<br>
				<table width="100%">
					<tr>
						<td colspan="2" id="conferenceInstallPrompt_Desc1"><b>AcuCONFERENCE Application([size]M)</b></td>
					</tr>
					<tr>
						<td>AcuCONFERENCE - Full function client.</td>
						<td><a href="javascript:downloadAuthor();">Install Now</a></td>
					</tr>
					<tr>
						<td colspan="2" id="conferenceInstallPrompt_Desc2"><b>AcuCONFERENCE Mini([size]M)</b></td>
					</tr>
					<tr>
						<td>AcuCONFERENCE Mini - Does not have recording capability, PDF support and application interface</td>
						<td><a href="javascript:downloadClient();">Install Now</a></td>
					</tr>
				</table>
			</span>

			<span id="patchInstallPrompt" name="patchInstallPrompt" style="display:none;">
			<table align="center">
				<tr>
					<td id="patchInstallPrompt_Desc1" style="display:none"><b>Upgrade program is available.</b></td>
				</tr>
				<tr>
					<td id="patchInstallPrompt_Desc2" style="display:none"><b>You need to install the version [Ver] to join the conference. <br><br>
						The installer will automatically uninstall your existing version before installing the version [Ver].<br><br>
						WARNING: You will need version [MVer] license key for AcuStudio [Ver]. <br>
						Please contact your reseller to obtain the license key if you are on maintenance contract. <br><br>
						Do you want to proceed with the installation?</b>
					</td>
				</tr>
				<tr>
					<td>
						<table align="center">
							<tr>
								<td><a href="javascript:downloadPatch();">Install Now</a></td>
								<td width="18"></td>
								<td><a href="javascript:skipUpdate();">Install Later</a></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</span>

			<span id="conferenceDownloadPrompt" name="conferenceDownloadPrompt" style="display:none;">
				<table width="100%">
					<tr>
						<td><center>
							<span id="conferenceInstallingPrompt" name="conferenceInstallingPrompt" style="display:none;">
								<b>Download Completed.... Initializing Setup</b>
							</span>
							<br>
							<div id="progressBar" name="progressBar" style="border-right: white 2px inset; border-top: white 2px inset; background: white;
						        border-left: white 2px inset; width: 300px; border-bottom: white 2px inset; height: 20px;
						        text-align: left">
						        <div id="sbChild1" style="
						           -moz-opacity: 0.8;width: 0%; position: relative; height: 20px;">
						            <div style="font-size: 1px; background: highlight; width: 100%; height: 20px">
						            </div>
						        </div>
						        <div style="font-size: 12px; width: 300px; color: black; font-family: arial; position: absolute;
						            text-align: center">
						            0%</div>
						    </div>
							<br>
							<br>
							<span id="conferenceDownloadPrompt_Cancel"><a href="javascript:cancelDownload();">Cancel download</a></span></center>
						</td>
					</tr>
				</table>
			</span>

			<span id="DirectXPrompt" name="DirectXPrompt" style="display:none;"><center>
				<b>AcuCONFERENCE needs to operate with Microsoft DirectX 9.0.<br>
				You can <a href='<?php echo  conf('idm_server');?>aculearn-idm/dxsetup/dxwebsetup.exe'>Click Here</a> to update your DirectX now.<br>
				Or you can download the latest DirectX version from <a href='http://www.microsoft.com/downloads/' target='_blank'>Microsoft download website</a><br>
				Notes: After Microsoft DirectX installation, you must reboot your computer.</b></center>
			</span>

			<span id="isAdminPrompt" name="isAdminPrompt" style="display:none;">
				<center><b>You do not have administrator rights to install AcuCONFERENCE.</b></center>
			</span>

			<span id="isValidOSPrompt" name="isValidOSPrompt" style="display:none;">
				<center><b>AcuCONFERENCE requires Windows 2000 OS or higher to run.</b></center>
			</span>

			<span id="installFailedPrompt" name="installFailedPrompt" style="display:none;">
				<center><b>Installation failed</b></center>
			</span>

			<span id="startPanel" name="startPanel" style="display:none;">
				<center><b>AcuCONFERENCE will start automatically.<br>
				If it does not start after a few seconds, please retry.</b></center>
			</span>

			<span id="startErrorPanel" name="startErrorPanel" style="display:none;">
				<center><b>Cannot start conference program,<br>
				please make sure you have installed the latest AcuCONFERENCE software or try to start conference from the AcuCONFERENCE program menu. </b></center>
			</span>


			<span id="startConfScript" name="startConfScript" style="display:none;">
		<?php
			echo '<script Language="javascript">
					var IsModerator = "1";
					var ClientId	= "'.$start->Records->Record->AuthorID.'";
					var ClientName	= "'.$start->Records->Record->AuthorDisplayName.'";
					var UserName	= ClientId + "||" + ClientName;
					var ManagerIp	= "'.$start->Records->Record->ManagerIp.'";
					var SessionPort = "'.$start->Records->Record->SessionPort.'";
					var	RetryTimes	= "3";
					var	AuthorName	= "'.$start->Records->Record->AuthorID.'";
					var	AuthorDisplayName	= "'.$start->Records->Record->AuthorDisplayName.'";
					var	SessionId	= "'.$start->Records->Record->SessionId.'";
					var	ModuleType	= "'.$start->Records->Record->ModuleType.'";
					var	StandAlone	= "'.$start->Records->Record->StandAlone.'";
					var ProjectBasePath = "'.$start->Records->Record->ProjectBasePath.'";
					var HasContent 	= "'.$start->Records->Record->HasContent.'";
					var	ConfTitle	= "'.$start->Records->Record->ConfTitle.'";
					var	ConfDesc	= "'.$start->Records->Record->ConfDesc.'";
					var	MaxUserCount		= "'.$start->Records->Record->MaxUserCount.'";
					var	MaxSpeakerCount		= "'.$start->Records->Record->MaxSpeakerCount.'";
					var	MaxSpeed			= "'.$start->Records->Record->MaxSpeed.'";
					var VBRMode 	= "'.$start->Records->Record->VBRMode.'";
					var ConfMode	= "'.$start->Records->Record->ConfMode.'";
					var ConfQuality	= "'.$start->Records->Record->ConfQuality.'";
					var QualityPower	= "'.$start->Records->Record->QualityPower.'";
					var AVMode		= "'.$start->Records->Record->AVMode.'";
					var StartMode	= "'.$start->Records->Record->StartMode.'";
					var AutoClearTOC  		= "'.$start->Records->Record->AutoClearTOC.'";
					var AuthorAccount	= "'.$start->Records->Record->AuthorAccount.'";
					var Company		= "'.$start->Records->Record->GroupId.'";
					var IsInConf	= "'.$start->Records->Record->IsInConf.'";

					function win_load(){
						if(ClientId == AuthorName && IsInConf == "1"){
							document.getElementById("userIsInConfPrompt").style.display = "block";
						}else if(ClientId != AuthorName && StartMode == "1"){
							document.getElementById("startConfByAuthorPrompt").style.display = "block";
						}else{
							load_session();
						}
						return;
					}

					function startConf(){
						document.getElementById("opr").SetAttribute("IsModerator", IsModerator);
						document.getElementById("opr").SetAttribute("ManagerIp", 	ManagerIp);
						document.getElementById("opr").SetAttribute("ASHost", 		"");
						document.getElementById("opr").SetAttribute("MainStreamIp","");
						document.getElementById("opr").SetAttribute("StreamIp",	"");
						document.getElementById("opr").SetAttribute("SessionPort", SessionPort);
						document.getElementById("opr").SetAttribute("RetryTimes", 	RetryTimes);
						document.getElementById("opr").SetAttribute("UserName", 	UserName);
						document.getElementById("opr").SetAttribute("AuthorName", 	AuthorName);
						document.getElementById("opr").SetAttribute("AuthorDisplayName", 	AuthorDisplayName);
						document.getElementById("opr").SetAttribute("UserAccount", 	AuthorAccount);
						document.getElementById("opr").SetAttribute("Company", 	Company);
						document.getElementById("opr").SetAttribute("ConfTitle", 	ConfTitle);
						document.getElementById("opr").SetAttribute("ConfDesc", 	ConfDesc);
						document.getElementById("opr").SetAttribute("SessionId", 	SessionId);
						document.getElementById("opr").SetAttribute("ModuleType", 	ModuleType);
						document.getElementById("opr").SetAttribute("StandAlone", 	StandAlone);
						document.getElementById("opr").SetAttribute("ASName", 		"");
						document.getElementById("opr").SetAttribute("ProjectBasePath", 	ProjectBasePath);
						document.getElementById("opr").SetAttribute("HasContent", 	HasContent);
						document.getElementById("opr").SetAttribute("MaxUserCount",MaxUserCount);
						document.getElementById("opr").SetAttribute("MaxSpeakerCount", 	MaxSpeakerCount);
						document.getElementById("opr").SetAttribute("MaxSpeed", 	"2048"); //MaxSpeed
						document.getElementById("opr").SetAttribute("VBRMode", 	VBRMode);
						document.getElementById("opr").SetAttribute("ConfMode", 	ConfMode);
						document.getElementById("opr").SetAttribute("ConfQuality", 	ConfQuality);
						document.getElementById("opr").SetAttribute("QualityPower", 	QualityPower);
						document.getElementById("opr").SetAttribute("AVMode", 		AVMode);
						document.getElementById("opr").SetAttribute("StartMode", 	StartMode);
						document.getElementById("opr").SetAttribute("AutoClearTOC",AutoClearTOC);
						document.getElementById("opr").SetAttribute("CallYou","'.$start->Records->Record->CallYou.'");
						document.getElementById("opr").SetAttribute("CallMe","'.$start->Records->Record->CallMe.'");
						document.getElementById("opr").SetAttribute("CallOut","'.$start->Records->Record->CallOut.'");
						document.getElementById("opr").SetAttribute("Monitor","'.$start->Records->Record->Monitor.'");
						document.getElementById("opr").SetAttribute("PresenterMode","'.$start->Records->Record->PresenterMode.'");
						document.getElementById("opr").SetAttribute("UseSSL","'.$start->Records->Record->UseSSL.'");
						document.getElementById("opr").SetAttribute("AutoAccept","'.$start->Records->Record->AutoAccept.'");
						document.getElementById("opr").SetAttribute("RecordingButton","'.$start->Records->Record->FreeRecording.'");
						document.getElementById("opr").SetAttribute("CBRPower","'.$start->Records->Record->CBRPower.'");


						if( !document.getElementById("opr").StartConference() )
						{
							document.getElementById("startPanel").style.display = "none";
							document.getElementById("startErrorPanel").style.display = "block";
						}
					}
				</script>
			</span>


		</td>
	</tr>
</table>
</center>
	</body>
</html>';
}
?>