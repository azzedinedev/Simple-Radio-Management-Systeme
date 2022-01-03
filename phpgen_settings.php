<?php

//  define('SHOW_VARIABLES', 1);
//  define('DEBUG_LEVEL', 1);

//  error_reporting(E_ALL ^ E_NOTICE);
//  ini_set('display_errors', 'On');

set_include_path('.' . PATH_SEPARATOR . get_include_path());


include_once dirname(__FILE__) . '/' . 'components/utils/system_utils.php';
include_once dirname(__FILE__) . '/' . 'components/mail/mailer.php';
include_once dirname(__FILE__) . '/' . 'components/mail/phpmailer_based_mailer.php';
require_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';

//  SystemUtils::DisableMagicQuotesRuntime();

SystemUtils::SetTimeZoneIfNeed('Europe/Belgrade');

function GetGlobalConnectionOptions()
{
    return
        array(
          'server' => 'localhost',
          'port' => '3306',
          'username' => 'root',
          'database' => 'rms',
          'client_encoding' => 'utf8'
        );
}

function HasAdminPage()
{
    return false;
}

function HasHomePage()
{
    return true;
}

function GetHomeURL()
{
    return 'index.php';
}

function GetHomePageBanner()
{
    return '';
}

function GetPageGroups()
{
    $result = array();
    $result[] = array('caption' => 'Administration', 'description' => '');
    $result[] = array('caption' => 'Dossiers Patients', 'description' => '');
    $result[] = array('caption' => 'Radiologie', 'description' => '');
    $result[] = array('caption' => 'Mon compte', 'description' => '');
    return $result;
}

function GetPageInfos()
{
    $result = array();
    $result[] = array('caption' => 'Mon profile', 'short_caption' => 'Mon profile', 'filename' => 'mon-profile.php', 'name' => 'users02', 'group_name' => 'Mon compte', 'add_separator' => false, 'description' => 'Affichage et modification du profile utilisateur en cours');
    $result[] = array('caption' => 'Reinitialiser le mot de passe', 'short_caption' => 'Reinitialiser le mot de passe', 'filename' => 'update-my-pssaword.php', 'name' => 'users03', 'group_name' => 'Mon compte', 'add_separator' => false, 'description' => 'Mise à jour du mot de passe utilisateur en cours');
    $result[] = array('caption' => 'Patients', 'short_caption' => 'Patients', 'filename' => 'patients.php', 'name' => 'patients', 'group_name' => 'Dossiers Patients', 'add_separator' => false, 'description' => 'Gestion de la liste des patients');
    $result[] = array('caption' => 'Demande de radiologie', 'short_caption' => 'Demande de radiologie', 'filename' => 'radio-requetes.php', 'name' => 'radiorequests', 'group_name' => 'Radiologie', 'add_separator' => false, 'description' => 'Gestion des demandes de radiologies');
    $result[] = array('caption' => 'Rapports de radiologie', 'short_caption' => 'Rapports de radiologie', 'filename' => 'radio-rapports.php', 'name' => 'Query Radio Response', 'group_name' => 'Radiologie', 'add_separator' => false, 'description' => 'Gestions des rapports de radiologie');
    $result[] = array('caption' => 'Type de radio', 'short_caption' => 'Type de radio', 'filename' => 'radio-types.php', 'name' => 'radiotypes', 'group_name' => 'Radiologie', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Utilisateurs', 'short_caption' => 'Utilisateurs', 'filename' => 'utilisateurs.php', 'name' => 'users', 'group_name' => 'Administration', 'add_separator' => false, 'description' => 'Gestion de la liste des utilisateurs');
    $result[] = array('caption' => 'Roles', 'short_caption' => 'Roles', 'filename' => 'roles.php', 'name' => 'roles', 'group_name' => 'Administration', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Modification mot de passe', 'short_caption' => 'Modification mot de passe', 'filename' => 'update-pssawords.php', 'name' => 'users01', 'group_name' => 'Administration', 'add_separator' => false, 'description' => 'Mise à jour du mot de passe utilisateur');
    $result[] = array('caption' => 'Demandeurs', 'short_caption' => 'Demandeurs', 'filename' => 'demandeurs.php', 'name' => 'Query Demandeurs', 'group_name' => 'Radiologie', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Album films radio', 'short_caption' => 'Album films radio', 'filename' => 'radio-gallerie-images.php', 'name' => 'Query Gallery with Response and Request', 'group_name' => 'Radiologie', 'add_separator' => false, 'description' => 'Gestion des images des films radiologiques annéxées au rapport radio');
    $result[] = array('caption' => 'Manipulateurs', 'short_caption' => 'Manipulateurs', 'filename' => 'manipulateurs.php', 'name' => 'Query Manipulateurs', 'group_name' => 'Radiologie', 'add_separator' => false, 'description' => '');
    return $result;
}

function GetPagesHeader()
{
    return
        '';
}

function GetPagesFooter()
{
    return
        '';
}

function ApplyCommonPageSettings(Page $page, Grid $grid)
{
    $page->SetShowUserAuthBar(true);
    $page->setShowNavigation(true);
    $page->OnCustomHTMLHeader->AddListener('Global_CustomHTMLHeaderHandler');
    $page->OnGetCustomTemplate->AddListener('Global_GetCustomTemplateHandler');
    $page->OnGetCustomExportOptions->AddListener('Global_OnGetCustomExportOptions');
    $page->getDataset()->OnGetFieldValue->AddListener('Global_OnGetFieldValue');
    $page->getDataset()->OnGetFieldValue->AddListener('OnGetFieldValue', $page);
    $grid->BeforeUpdateRecord->AddListener('Global_BeforeUpdateHandler');
    $grid->BeforeDeleteRecord->AddListener('Global_BeforeDeleteHandler');
    $grid->BeforeInsertRecord->AddListener('Global_BeforeInsertHandler');
    $grid->AfterUpdateRecord->AddListener('Global_AfterUpdateHandler');
    $grid->AfterDeleteRecord->AddListener('Global_AfterDeleteHandler');
    $grid->AfterInsertRecord->AddListener('Global_AfterInsertHandler');
}

function GetAnsiEncoding() { return 'windows-1252'; }

function Global_OnGetCustomPagePermissionsHandler(Page $page, PermissionSet &$permissions, &$handled)
{

}

function Global_CustomHTMLHeaderHandler($page, &$customHtmlHeaderText)
{
    $customHtmlHeaderText  =  '<link rel="stylesheet" href="components/js/libs/fancybox/jquery.fancybox.min.css">';
    $customHtmlHeaderText .= "\n";
}

function Global_GetCustomTemplateHandler($type, $part, $mode, &$result, &$params, CommonPage $page = null)
{

}

function Global_OnGetCustomExportOptions($page, $exportType, $rowData, &$options)
{

}

function Global_OnGetFieldValue($fieldName, &$value, $tableName)
{

}

function Global_GetCustomPageList(CommonPage $page, PageList $pageList)
{

}

function Global_BeforeUpdateHandler($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
{

}

function Global_BeforeDeleteHandler($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
{

}

function Global_BeforeInsertHandler($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
{

}

function Global_AfterUpdateHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
{

}

function Global_AfterDeleteHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
{

}

function Global_AfterInsertHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
{

}

function GetDefaultDateFormat()
{
    return 'Y-m-d';
}

function GetFirstDayOfWeek()
{
    return 0;
}

function GetPageListType()
{
    return PageList::TYPE_SIDEBAR;
}

function GetNullLabel()
{
    return null;
}

function UseMinifiedJS()
{
    return true;
}

function GetOfflineMode()
{
    return false;
}

function GetInactivityTimeout()
{
    return 0;
}

function GetMailer()
{
    $mailerOptions = new MailerOptions(MailerType::Mail, 'noreply@eph-hassimessaoud.com', 'EPH Hassi Messaoud');
    
    return PHPMailerBasedMailer::getInstance($mailerOptions);
}

function sendMailMessage($recipients, $messageSubject, $messageBody, $attachments = '', $cc = '', $bcc = '')
{
    GetMailer()->send($recipients, $messageSubject, $messageBody, $attachments, $cc, $bcc);
}

function createConnection()
{
    $connectionOptions = GetGlobalConnectionOptions();
    $connectionOptions['client_encoding'] = 'utf8';

    $connectionFactory = MySqlIConnectionFactory::getInstance();
    return $connectionFactory->CreateConnection($connectionOptions);
}
