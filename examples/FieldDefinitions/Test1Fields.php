<?PHP
$def['field1']['name'] = 'forename';
$def['field1']['filters'] = array(new TrimFilter('forename'));
$def['field1']['rules'][0]['ruleType'] = new RequiredRule('forename','forename is required');
$def['field1']['rules'][0]['processingCommand']= IInputProcessor::PROCESS_STOP;
$def['field1']['rules'][1]['ruleType'] = new LengthRule('forename','forename should be between 2 and 20 chars',2,20);
$def['field1']['rules'][1]['processingCommand']= IInputProcessor::PROCESS_STOP;

$def['field2']['name'] = 'surname';
$def['field2']['filters'] = array(new TrimFilter('surname'));
$def['field2']['rules'][0]['ruleType'] = new LengthRule('surname','surname should be between 5 and 20 chars',5,20);
$def['field2']['rules'][0]['processingCommand'] = IInputProcessor::PROCESS_STOP;
?>
