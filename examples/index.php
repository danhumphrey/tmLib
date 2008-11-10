<?php
error_reporting (E_ALL);

// tmLib
require_once('../classes/tmLib.php');
TmLib::init();
AutoLoad::setCacheFile('cache.inc');

function __autoload($className) {
	AutoLoad::loadClass($className);
}

?>
<h1>Test Server Page </h1>
<form id="pageForm" name="pageForm" method="get" action="testPage.php">
  <p><strong>Page Name:</strong>
    <input name="page" type="text" id="page" />
</p>
  <p>
    <input type="submit" name="Submit" value="Submit" />
</p>
</form>

<h1>Test Command </h1>
<form id="commandForm" name="commandForm" method="get" action="testCommand.php">
  <p><strong>Command Name:</strong>
      <input name="cmd" type="text" id="cmd" />
  </p>
  <p>
    <input type="submit" name="Submit2" value="Submit" />
  </p>
</form>
<h1>Test Captcha </h1>
<form id="commandForm" name="commandForm" method="get" action="testCaptcha.php">
  <p><strong>Captcha:</strong>
      <input name="captchaText" type="text" id="captchaText" />
     
      <img src="captcha.php?rand=<?php echo md5(microtime() * mktime()); ?>" /></p>
  <p>
    <input type="submit" name="Submit22" value="Submit" />
  </p>
</form>
<p></p>
<p>&nbsp;</p>
