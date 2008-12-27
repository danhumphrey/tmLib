<?php

require_once('classes/Core/AutoLoad.php');
require_once('classes/Core/IDataSet.php');
require_once('classes/Core/DataSet.php');
require_once('classes/Core/Registry.php');
require_once('classes/Core/Session.php');

require_once('classes/Database/ResultPager.php');

require_once('classes/AccessControl/AuthException.php');
require_once('classes/AccessControl/IAuthValidator.php');
require_once('classes/AccessControl/AuthDefinition.php');
require_once('classes/AccessControl/Auth.php');

require_once('classes/Http/HttpRequest.php');
require_once('classes/Http/HttpAjaxResponse.php');
require_once('classes/Http/HttpResponse.php');
require_once('classes/Http/HttpPathVars.php');


require_once('classes/RequestMappers/BaseRequestMapper.php');
require_once('classes/RequestMappers/CommandMapper.php');
require_once('classes/RequestMappers/PageMapper.php');
require_once('classes/RequestMappers/IRequestHandler.php');


require_once('classes/Input/IInputProcessor.php');
require_once('classes/Input/InputProcessor.php');
require_once('classes/Input/InputField.php');


require_once('classes/Input/Rules/BaseRule.php');
require_once('classes/Input/Rules/OneRequiredRule.php');
require_once('classes/Input/Rules/RequiredRule.php');
require_once('classes/Input/Rules/EmailRule.php');
require_once('classes/Input/Rules/NumericRule.php');
require_once('classes/Input/Rules/ConditionalRequiredRule.php');
require_once('classes/Input/Rules/LengthRule.php');
require_once('classes/Input/Rules/ComparisonRule.php');
require_once('classes/Input/Rules/PatternRule.php');
require_once('classes/Input/Rules/AllowedValuesRule.php');
require_once('classes/Input/Rules/CallbackRule.php');

require_once('classes/Input/Filters/BaseFilter.php');
require_once('classes/Input/Filters/ConcatFilter.php');
require_once('classes/Input/Filters/TrimFilter.php');
require_once('classes/Input/Filters/StripTagsFilter.php');

require_once('classes/Commands/ICommandState.php');
require_once('classes/Commands/BaseCommand.php');
require_once('classes/Commands/ServerPage.php');

//require_once('PHPTal.php');

//require_once('classes/Views/PhpTalView.php');

require_once('classes/Core/Dispatcher.php');

//query abstraction
require_once('classes/Database/TmPdoDb.php');
require_once('classes/Database/TmQuery.php');
require_once('classes/Database/TmQueryCriteria.php');
require_once('classes/Database/TmQueryExpression.php');
require_once('classes/Database/TmWhereQuery.php');
require_once('classes/Database/TmSelectQuery.php');
require_once('classes/Database/TmInsertQuery.php');
require_once('classes/Database/TmUpdateQuery.php');
require_once('classes/Database/TmDeleteQuery.php');
require_once('classes/Database/TmSubSelectQuery.php');


//ORM
require_once('classes/Orm/IDefinitionParser.php');
require_once('classes/Orm/OrmAnnotationParser.php');
require_once('classes/Orm/OrmDefinition.php');
require_once('classes/Orm/OrmObject.php');
require_once('classes/Orm/Orm.php');

//db
define('PDO_DSN','mysql:host=localhost;dbname=query_test');
define('PDO_USER','root');
define('PDO_PW','');
//require_once('classes/Controllers/ControllerBase.php');

define('ORM_DSN','mysql:host=localhost;dbname=orm_test');



// Constants:
define ('DIRSEP', DIRECTORY_SEPARATOR);
$site_path = realpath(dirname(__FILE__) . DIRSEP . '..' . DIRSEP) . DIRSEP;
define ('SITE_PATH', $site_path);


?>