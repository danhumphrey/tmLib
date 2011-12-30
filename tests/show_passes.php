<strong><?php
    //tests includes
	require_once('test-includes.php');
    require_once(SIMPLE_TEST . 'reporter.php');
    require_once('my_reporter.php');

class ShowPasses extends MyReporter {

    function ShowPasses() {
        $this->HtmlReporter();
    }
    function paintPass($message) {
        parent::paintPass($message);
        print '<table style="font-size: 0.9em"><tr>'."\n";
        print "<td nowrap><span class=\"pass\">Pass</span></td>";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print '<td nowrap>'.implode("-&gt;", $breadcrumb).'</td>';
        print '<td nowrap>'."-&gt;$message</td></tr></table>\n";
    }

}
?>