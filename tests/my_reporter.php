<strong><?php
    //tests includes
	require_once('test-includes.php');
    require_once(SIMPLE_TEST . 'reporter.php');

class MyReporter extends HtmlReporter {

    function MyReporter() {
        $this->HtmlReporter();
    }
    /*
	function paintFail($message) {
    	parent::paintFail($message);

    	print '<table style="font-size: 0.9em"><tr>'."\n";
        print "<td nowrap><span class=\"fail\">Fail</span></td>";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print '<td nowrap>'.implode("-&gt;", $breadcrumb).'</td>';
        print '<td nowrap>'."-&gt;$message</td></tr></table>\n";
    }
*/
}
?>