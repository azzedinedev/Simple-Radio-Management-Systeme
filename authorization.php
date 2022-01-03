<?php

require_once 'phpgen_settings.php';
require_once 'components/application.php';
require_once 'components/security/permission_set.php';
require_once 'components/security/user_authentication/table_based_user_authentication.php';
require_once 'components/security/grant_manager/user_grant_manager.php';
require_once 'components/security/grant_manager/composite_grant_manager.php';
require_once 'components/security/grant_manager/hard_coded_user_grant_manager.php';
require_once 'components/security/grant_manager/table_based_user_grant_manager.php';
require_once 'components/security/table_based_user_manager.php';

include_once 'components/security/user_identity_storage/user_identity_session_storage.php';

require_once 'database_engine/mysql_engine.php';

$grants = array('guest' => 
        array()
    ,
    'defaultUser' => 
        array('users02' => new PermissionSet(false, false, false, false),
        'users03' => new PermissionSet(false, false, false, false),
        'patients' => new PermissionSet(false, false, false, false),
        'radiorequests' => new PermissionSet(false, false, false, false),
        'radioresponse' => new PermissionSet(false, false, false, false),
        'Query Radio Response' => new PermissionSet(false, false, false, false),
        'Query Radio Response.Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'radiotypes' => new PermissionSet(false, false, false, false),
        'radioresponsegallery' => new PermissionSet(false, false, false, false),
        'users' => new PermissionSet(false, false, false, false),
        'roles' => new PermissionSet(false, false, false, false),
        'users01' => new PermissionSet(false, false, false, false),
        'Query Demandeurs' => new PermissionSet(false, false, false, false),
        'Query Demandeurs.radiorequests' => new PermissionSet(false, false, false, false),
        'Query Request Details' => new PermissionSet(false, false, false, false),
        'Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'requestusers' => new PermissionSet(false, false, false, false),
        'responseusers' => new PermissionSet(false, false, false, false),
        'Query Response Details' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs.radioresponse' => new PermissionSet(false, false, false, false),
        'Query Users details' => new PermissionSet(false, false, false, false))
    ,
    'admin' => 
        array('users02' => new PermissionSet(false, false, false, false),
        'users03' => new PermissionSet(false, false, false, false),
        'patients' => new PermissionSet(false, false, false, false),
        'radiorequests' => new PermissionSet(false, false, false, false),
        'radioresponse' => new PermissionSet(false, false, false, false),
        'Query Radio Response' => new PermissionSet(false, false, false, false),
        'Query Radio Response.Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'radiotypes' => new PermissionSet(false, false, false, false),
        'radioresponsegallery' => new PermissionSet(false, false, false, false),
        'users' => new PermissionSet(false, false, false, false),
        'roles' => new PermissionSet(false, false, false, false),
        'users01' => new PermissionSet(false, false, false, false),
        'Query Demandeurs' => new PermissionSet(false, false, false, false),
        'Query Demandeurs.radiorequests' => new PermissionSet(false, false, false, false),
        'Query Request Details' => new PermissionSet(false, false, false, false),
        'Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'requestusers' => new PermissionSet(false, false, false, false),
        'responseusers' => new PermissionSet(false, false, false, false),
        'Query Response Details' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs.radioresponse' => new PermissionSet(false, false, false, false),
        'Query Users details' => new PermissionSet(false, false, false, false))
    ,
    'manipulateur01' => 
        array('users02' => new PermissionSet(false, false, false, false),
        'users03' => new PermissionSet(false, false, false, false),
        'patients' => new PermissionSet(false, false, false, false),
        'radiorequests' => new PermissionSet(false, false, false, false),
        'radioresponse' => new PermissionSet(false, false, false, false),
        'Query Radio Response' => new PermissionSet(false, false, false, false),
        'Query Radio Response.Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'radiotypes' => new PermissionSet(false, false, false, false),
        'radioresponsegallery' => new PermissionSet(false, false, false, false),
        'users' => new PermissionSet(false, false, false, false),
        'roles' => new PermissionSet(false, false, false, false),
        'users01' => new PermissionSet(false, false, false, false),
        'Query Demandeurs' => new PermissionSet(false, false, false, false),
        'Query Demandeurs.radiorequests' => new PermissionSet(false, false, false, false),
        'Query Request Details' => new PermissionSet(false, false, false, false),
        'Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'requestusers' => new PermissionSet(false, false, false, false),
        'responseusers' => new PermissionSet(false, false, false, false),
        'Query Response Details' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs.radioresponse' => new PermissionSet(false, false, false, false),
        'Query Users details' => new PermissionSet(false, false, false, false))
    ,
    'manipulateur02' => 
        array('users02' => new PermissionSet(false, false, false, false),
        'users03' => new PermissionSet(false, false, false, false),
        'patients' => new PermissionSet(false, false, false, false),
        'radiorequests' => new PermissionSet(false, false, false, false),
        'radioresponse' => new PermissionSet(false, false, false, false),
        'Query Radio Response' => new PermissionSet(false, false, false, false),
        'Query Radio Response.Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'radiotypes' => new PermissionSet(false, false, false, false),
        'radioresponsegallery' => new PermissionSet(false, false, false, false),
        'users' => new PermissionSet(false, false, false, false),
        'roles' => new PermissionSet(false, false, false, false),
        'users01' => new PermissionSet(false, false, false, false),
        'Query Demandeurs' => new PermissionSet(false, false, false, false),
        'Query Demandeurs.radiorequests' => new PermissionSet(false, false, false, false),
        'Query Request Details' => new PermissionSet(false, false, false, false),
        'Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'requestusers' => new PermissionSet(false, false, false, false),
        'responseusers' => new PermissionSet(false, false, false, false),
        'Query Response Details' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs.radioresponse' => new PermissionSet(false, false, false, false),
        'Query Users details' => new PermissionSet(false, false, false, false))
    ,
    'pediatre01' => 
        array('users02' => new PermissionSet(false, false, false, false),
        'users03' => new PermissionSet(false, false, false, false),
        'patients' => new PermissionSet(false, false, false, false),
        'radiorequests' => new PermissionSet(false, false, false, false),
        'radioresponse' => new PermissionSet(false, false, false, false),
        'Query Radio Response' => new PermissionSet(false, false, false, false),
        'Query Radio Response.Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'radiotypes' => new PermissionSet(false, false, false, false),
        'radioresponsegallery' => new PermissionSet(false, false, false, false),
        'users' => new PermissionSet(false, false, false, false),
        'roles' => new PermissionSet(false, false, false, false),
        'users01' => new PermissionSet(false, false, false, false),
        'Query Demandeurs' => new PermissionSet(false, false, false, false),
        'Query Demandeurs.radiorequests' => new PermissionSet(false, false, false, false),
        'Query Request Details' => new PermissionSet(false, false, false, false),
        'Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'requestusers' => new PermissionSet(false, false, false, false),
        'responseusers' => new PermissionSet(false, false, false, false),
        'Query Response Details' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs.radioresponse' => new PermissionSet(false, false, false, false),
        'Query Users details' => new PermissionSet(false, false, false, false))
    ,
    'pneumologue' => 
        array('users02' => new PermissionSet(false, false, false, false),
        'users03' => new PermissionSet(false, false, false, false),
        'patients' => new PermissionSet(false, false, false, false),
        'radiorequests' => new PermissionSet(false, false, false, false),
        'radioresponse' => new PermissionSet(false, false, false, false),
        'Query Radio Response' => new PermissionSet(false, false, false, false),
        'Query Radio Response.Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'radiotypes' => new PermissionSet(false, false, false, false),
        'radioresponsegallery' => new PermissionSet(false, false, false, false),
        'users' => new PermissionSet(false, false, false, false),
        'roles' => new PermissionSet(false, false, false, false),
        'users01' => new PermissionSet(false, false, false, false),
        'Query Demandeurs' => new PermissionSet(false, false, false, false),
        'Query Demandeurs.radiorequests' => new PermissionSet(false, false, false, false),
        'Query Request Details' => new PermissionSet(false, false, false, false),
        'Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'requestusers' => new PermissionSet(false, false, false, false),
        'responseusers' => new PermissionSet(false, false, false, false),
        'Query Response Details' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs.radioresponse' => new PermissionSet(false, false, false, false),
        'Query Users details' => new PermissionSet(false, false, false, false))
    ,
    'manipulateur03' => 
        array('users02' => new PermissionSet(false, false, false, false),
        'users03' => new PermissionSet(false, false, false, false),
        'patients' => new PermissionSet(false, false, false, false),
        'radiorequests' => new PermissionSet(false, false, false, false),
        'radioresponse' => new PermissionSet(false, false, false, false),
        'Query Radio Response' => new PermissionSet(false, false, false, false),
        'Query Radio Response.Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'radiotypes' => new PermissionSet(false, false, false, false),
        'radioresponsegallery' => new PermissionSet(false, false, false, false),
        'users' => new PermissionSet(false, false, false, false),
        'roles' => new PermissionSet(false, false, false, false),
        'users01' => new PermissionSet(false, false, false, false),
        'Query Demandeurs' => new PermissionSet(false, false, false, false),
        'Query Demandeurs.radiorequests' => new PermissionSet(false, false, false, false),
        'Query Request Details' => new PermissionSet(false, false, false, false),
        'Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'requestusers' => new PermissionSet(false, false, false, false),
        'responseusers' => new PermissionSet(false, false, false, false),
        'Query Response Details' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs.radioresponse' => new PermissionSet(false, false, false, false),
        'Query Users details' => new PermissionSet(false, false, false, false))
    ,
    'manipulateur04' => 
        array('users02' => new PermissionSet(false, false, false, false),
        'users03' => new PermissionSet(false, false, false, false),
        'patients' => new PermissionSet(false, false, false, false),
        'radiorequests' => new PermissionSet(false, false, false, false),
        'radioresponse' => new PermissionSet(false, false, false, false),
        'Query Radio Response' => new PermissionSet(false, false, false, false),
        'Query Radio Response.Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'radiotypes' => new PermissionSet(false, false, false, false),
        'radioresponsegallery' => new PermissionSet(false, false, false, false),
        'users' => new PermissionSet(false, false, false, false),
        'roles' => new PermissionSet(false, false, false, false),
        'users01' => new PermissionSet(false, false, false, false),
        'Query Demandeurs' => new PermissionSet(false, false, false, false),
        'Query Demandeurs.radiorequests' => new PermissionSet(false, false, false, false),
        'Query Request Details' => new PermissionSet(false, false, false, false),
        'Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'requestusers' => new PermissionSet(false, false, false, false),
        'responseusers' => new PermissionSet(false, false, false, false),
        'Query Response Details' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs.radioresponse' => new PermissionSet(false, false, false, false),
        'Query Users details' => new PermissionSet(false, false, false, false))
    ,
    'demandeur02' => 
        array('users02' => new PermissionSet(false, false, false, false),
        'users03' => new PermissionSet(false, false, false, false),
        'patients' => new PermissionSet(false, false, false, false),
        'radiorequests' => new PermissionSet(false, false, false, false),
        'radioresponse' => new PermissionSet(false, false, false, false),
        'Query Radio Response' => new PermissionSet(false, false, false, false),
        'Query Radio Response.Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'radiotypes' => new PermissionSet(false, false, false, false),
        'radioresponsegallery' => new PermissionSet(false, false, false, false),
        'users' => new PermissionSet(false, false, false, false),
        'roles' => new PermissionSet(false, false, false, false),
        'users01' => new PermissionSet(false, false, false, false),
        'Query Demandeurs' => new PermissionSet(false, false, false, false),
        'Query Demandeurs.radiorequests' => new PermissionSet(false, false, false, false),
        'Query Request Details' => new PermissionSet(false, false, false, false),
        'Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'requestusers' => new PermissionSet(false, false, false, false),
        'responseusers' => new PermissionSet(false, false, false, false),
        'Query Response Details' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs.radioresponse' => new PermissionSet(false, false, false, false),
        'Query Users details' => new PermissionSet(false, false, false, false))
    ,
    'demandeur03' => 
        array('users02' => new PermissionSet(false, false, false, false),
        'users03' => new PermissionSet(false, false, false, false),
        'patients' => new PermissionSet(false, false, false, false),
        'radiorequests' => new PermissionSet(false, false, false, false),
        'radioresponse' => new PermissionSet(false, false, false, false),
        'Query Radio Response' => new PermissionSet(false, false, false, false),
        'Query Radio Response.Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'radiotypes' => new PermissionSet(false, false, false, false),
        'radioresponsegallery' => new PermissionSet(false, false, false, false),
        'users' => new PermissionSet(false, false, false, false),
        'roles' => new PermissionSet(false, false, false, false),
        'users01' => new PermissionSet(false, false, false, false),
        'Query Demandeurs' => new PermissionSet(false, false, false, false),
        'Query Demandeurs.radiorequests' => new PermissionSet(false, false, false, false),
        'Query Request Details' => new PermissionSet(false, false, false, false),
        'Query Gallery with Response and Request' => new PermissionSet(false, false, false, false),
        'requestusers' => new PermissionSet(false, false, false, false),
        'responseusers' => new PermissionSet(false, false, false, false),
        'Query Response Details' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs' => new PermissionSet(false, false, false, false),
        'Query Manipulateurs.radioresponse' => new PermissionSet(false, false, false, false),
        'Query Users details' => new PermissionSet(false, false, false, false))
    );

$appGrants = array('guest' => new PermissionSet(false, false, false, false),
    'defaultUser' => new AdminPermissionSet(),
    'admin' => new AdminPermissionSet(),
    'manipulateur01' => new AdminPermissionSet(),
    'manipulateur02' => new AdminPermissionSet(),
    'pediatre01' => new AdminPermissionSet(),
    'pneumologue' => new AdminPermissionSet(),
    'manipulateur03' => new AdminPermissionSet(),
    'manipulateur04' => new AdminPermissionSet(),
    'demandeur02' => new AdminPermissionSet(),
    'demandeur03' => new AdminPermissionSet());

$dataSourceRecordPermissions = array();

$tableCaptions = array('users02' => 'Mon profile',
'users03' => 'Reinitialiser le mot de passe',
'patients' => 'Patients',
'radiorequests' => 'Demande de radiologie',
'radioresponse' => 'Rapport de radiologie',
'Query Radio Response' => 'Rapports de radiologie',
'Query Radio Response.Query Gallery with Response and Request' => 'Rapports de radiologie->Album films',
'radiotypes' => 'Type de radio',
'radioresponsegallery' => 'Album films radio',
'users' => 'Utilisateurs',
'roles' => 'Roles',
'users01' => 'Modification mot de passe',
'Query Demandeurs' => 'Demandeurs',
'Query Demandeurs.radiorequests' => 'Demandeurs->Demandes',
'Query Request Details' => 'Query Request Details',
'Query Gallery with Response and Request' => 'Album films radio',
'requestusers' => 'Demandeurs',
'responseusers' => 'Manipulateurs',
'Query Response Details' => 'Query Response Details',
'Query Manipulateurs' => 'Manipulateurs',
'Query Manipulateurs.radioresponse' => 'Manipulateurs->Manipultations',
'Query Users details' => 'Query Users Details');

$usersTableInfo = array(
    'TableName' => 'users',
    'UserId' => 'UserId',
    'UserName' => 'UserName',
    'Password' => 'UserPassword',
    'Email' => 'Mail',
    'UserToken' => 'Token',
    'UserStatus' => 'UserStatus'
);

function EncryptPassword($password, &$result)
{

}

function VerifyPassword($enteredPassword, $encryptedPassword, &$result)
{

}

function BeforeUserRegistration($username, $email, $password, &$allowRegistration, &$errorMessage)
{

}    

function AfterUserRegistration($username, $email)
{

}    

function PasswordResetRequest($username, $email)
{

}

function PasswordResetComplete($username, $email)
{

}

function CreatePasswordHasher()
{
    $hasher = CreateHasher('MD5');
    if ($hasher instanceof CustomStringHasher) {
        $hasher->OnEncryptPassword->AddListener('EncryptPassword');
        $hasher->OnVerifyPassword->AddListener('VerifyPassword');
    }
    return $hasher;
}

function CreateTableBasedGrantManager()
{
    return null;
}

function CreateTableBasedUserManager() {
    global $usersTableInfo;
    return new TableBasedUserManager(MySqlIConnectionFactory::getInstance(), GetGlobalConnectionOptions(), $usersTableInfo, CreatePasswordHasher(), true);
}

function SetUpUserAuthorization()
{
    global $grants;
    global $appGrants;
    global $dataSourceRecordPermissions;

    $hasher = CreatePasswordHasher();

    $hardCodedGrantManager = new HardCodedUserGrantManager($grants, $appGrants);
    $tableBasedGrantManager = CreateTableBasedGrantManager();
    $grantManager = new CompositeGrantManager();
    $grantManager->AddGrantManager($hardCodedGrantManager);
    if (!is_null($tableBasedGrantManager)) {
        $grantManager->AddGrantManager($tableBasedGrantManager);
    }

    $userAuthentication = new TableBasedUserAuthentication(new UserIdentitySessionStorage($hasher), false, $hasher, CreateTableBasedUserManager(), false, false, true);

    GetApplication()->SetUserAuthentication($userAuthentication);
    GetApplication()->SetUserGrantManager($grantManager);
    GetApplication()->SetDataSourceRecordPermissionRetrieveStrategy(new HardCodedDataSourceRecordPermissionRetrieveStrategy($dataSourceRecordPermissions));
}
