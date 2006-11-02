<?php
/**=============================================================================
       	GUnet e-Class 2.0 
        E-learning and Course Management Program  
================================================================================
       	Copyright(c) 2003-2006  Greek Universities Network - GUnet
        � full copyright notice can be read in "/info/copyright.txt".
        
       	Authors:    Costas Tsibanis <k.tsibanis@noc.uoa.gr>
        	    Yannis Exidaridis <jexi@noc.uoa.gr> 
      		    Alexandros Diamantidis <adia@noc.uoa.gr> 

        For a full list of contributors, see "credits.txt".  
     
        This program is a free software under the terms of the GNU 
        (General Public License) as published by the Free Software 
        Foundation. See the GNU License for more details. 
        The full license can be read in "license.txt".
     
       	Contact address: GUnet Asynchronous Teleteaching Group, 
        Network Operations Center, University of Athens, 
        Panepistimiopolis Ilissia, 15784, Athens, Greece
        eMail: eclassadmin@gunet.gr
==============================================================================*/

/**===========================================================================
	searchcours.php
	@last update: 31-05-2006 by Pitsiougas Vagelis
	@authors list: Karatzidis Stratos <kstratos@uom.gr>
		       Pitsiougas Vagelis <vagpits@uom.gr>
==============================================================================        
        @Description: A form to perform search for courses

 	This script allows the administrator to perform a search on courses by
 	title, code, type and faculte

 	The user can : - Fill the search form
 	               - Submit the search
                 - Return to course list

 	@Comments: The script is organised in three sections.

  1) Perform a search
  2) Start a new search
  3) Display all on an HTML page
  
==============================================================================*/

/*****************************************************************************
		DEAL WITH LANGFILES, BASETHEME, OTHER INCLUDES AND NAMETOOLS
******************************************************************************/
// Set the langfiles needed
$langFiles = array('gunet','admin','registration');
// Check if user is administrator and if yes continue
// Othewise exit with appropriate message
$require_admin = TRUE;
// Include baseTheme
include '../../include/baseTheme.php';
// Define $nameTools
$nameTools = $langSearchCourse;
$navigation[] = array("url" => "index.php", "name" => $langAdmin);
// Initialise $tool_content
$tool_content = "";

/*****************************************************************************
		MAIN BODY
******************************************************************************/
// Destroy search varialbles from session
if (isset($new) && ($new=="yes")) {
	session_unregister('searchtitle');
	session_unregister('searchcode');
	session_unregister('searchtype');
	session_unregister('searchfaculte');
	unset($searchtitle);
	unset($searchcode);
	unset($searchtype);
	unset($searchfaculte);
}
// Display link for new search if there is one already
if (isset($searchtitle) && isset($searchcode) && isset($searchtype) && isset($searchfaculte)) {
	$newsearch = "(<a href=\"searchcours.php?new=yes\">".$langNewSearch."</a>)";
}
	
	// Constract search form
	$tool_content .= "<form action=\"listcours.php?search=yes\" method=\"post\">";
	$tool_content .= "<table width=\"99%\"><caption>".$langSearchCriteria." ".@$newsearch."</caption><tbody>";
	$tool_content .= "  <tr>
    <td width=\"3%\" nowrap><b>������:</b></td>
    <td><input type=\"text\" name=\"formsearchtitle\" size=\"40\" value=\"".@$searchtitle."\"></td>
</tr>";
	$tool_content .= "  <tr>
    <td width=\"3%\" nowrap><b>�������:</b></td>
    <td><input type=\"text\" name=\"formsearchcode\" size=\"40\" value=\"".@$searchcode."\"></td>
</tr>";
	switch (@$searchcode) {
		case "2":
			$typeSel[2] = "selected";
			break;
		case "1":
			$typeSel[1] = "selected";
			break;
		case "0":
			$typeSel[0] = "selected";
			break;
		default:
			$typeSel[-1] = "selected";
			break;
	}
	$tool_content .= "  <tr>
    <td width=\"3%\" nowrap><b>����� ���������:</b></td>
    <td>
      <select name=\"formsearchtype\">
      	<option value=\"-1\" ".$typeSel[-1].">���</option>
        <option value=\"2\" ".@$typeSel[2].">�������</option>
        <option value=\"1\" ".@$typeSel[1].">������� �� �������</option>
        <option value=\"0\" ".@$typeSel[0].">�������</option>
      </select>
    </td>
</tr>";
	$tool_content .= "  <tr>
    <td width=\"3%\" nowrap><b>".$langDepartment.":</b></td>
    <td><select name=\"formsearchfaculte\">
    	<option value=\"0\">���</option>\n";
  
$resultFac=mysql_query("SELECT name FROM faculte ORDER BY number");

	while ($myfac = mysql_fetch_array($resultFac)) {	
		if($myfac['name'] == @$searchfaculte) 
			$tool_content .= "      <option selected>$myfac[name]</option>";
		else 
			$tool_content .= "      <option>$myfac[name]</option>";
	}
	$tool_content .= "</select>
    </td>
  </tr>";  
	$tool_content .= "  <tr>
    <td colspan=\"2\"><br><input type='submit' name='search_submit' value='$langSearch'></td>
  </tr>";
	$tool_content .= "</tbody></table></form>";

	// Display link to go back to index.php
	$tool_content .= "<center><p><a href=\"index.php\">".$langReturn."</a></p></center>";

/*****************************************************************************
		DISPLAY HTML
******************************************************************************/
// Call draw function to display the HTML
// $tool_content: the content to display
// 3: display administrator menu
// admin: use tool.css from admin folder
draw($tool_content,3,'admin');
?>
