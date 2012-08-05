  
/*------------------------------------------------------------
/*   Switch Inner HTML of first div with second
/*-----------------------------------------------------------*/
function jsutilSwitchDivInnerHTML(div1ID,div2ID){
	var div1 = document.getElementById(div1ID);
	var div2 = document.getElementById(div2ID);
	div1.innerHTML = div2.innerHTML;
	return true;
}

/*------------------------------------------------------------
/* adjustMinDeductSelection(e,minVal)
/*   Adjust minimum selection in a list of checkboxes
/*-----------------------------------------------------------*/

function jsutilAdjustMinSelection(e,minVal){
    if (! e.length) return false;    // not a multi-dimensional element
	for (i=0;i < e.length;i++){
		if (e(i).value == minVal){
			e(i).checked = true;
			e(i).disabled = false;
		}
		else {
			var valCmp = e(i).value - 0;  // force conversion
			if (valCmp < minDeductible){
				e(i).checked = false;
				e(i).disabled = true;
			}
			else e(i).disabled = false;
		}
	}
	
	return true;

  }

/**********************************************************************/
function toggleField(e,pName){
//  Toggle the enabled value for a field in the same form with name=pName
/**********************************************************************/
	if (! pName){ pName = e.name};
	e1 = eval(e.form.pName);
	dFlag = e1.disabled;
	e1.disabled = (! dFlag);
	return true;
}

/**********************************************************************
//  popUp(URL)
// pop-up a separate browser window  e.g, for help screens
/**********************************************************************/
<!-- Idea by:  Nic Wolfe (Nic@TimelapseProductions.com) -->
<!-- Web URL:  http://fineline.xs.mw -->

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

function popUp(URL){
//debugger;
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=1,width=650,height=600');");
}

