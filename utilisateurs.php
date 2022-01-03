<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

    include_once dirname(__FILE__) . '/components/startup.php';
    include_once dirname(__FILE__) . '/components/application.php';


    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page/page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/detail_page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/nested_form_page.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthentication()->applyIdentityToConnectionOptions($result);
        return $result;
    }

    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class usersPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`users`');
            $field = new IntegerField('UserId', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('UserName');
            $this->dataset->AddField($field, false);
            $field = new StringField('UserPassword');
            $this->dataset->AddField($field, false);
            $field = new StringField('UserFullName');
            $this->dataset->AddField($field, false);
            $field = new StringField('Mail');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('Token');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('UserSecretQestion');
            $this->dataset->AddField($field, false);
            $field = new StringField('UserSecretAnswer');
            $this->dataset->AddField($field, false);
            $field = new StringField('UserStatus');
            $this->dataset->AddField($field, false);
            $field = new StringField('UserDescription');
            $this->dataset->AddField($field, false);
            $field = new StringField('Roles');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'UserId', 'UserId', 'Id'),
                new FilterColumn($this->dataset, 'UserName', 'UserName', 'Pseudo'),
                new FilterColumn($this->dataset, 'UserPassword', 'UserPassword', 'Mot de passe'),
                new FilterColumn($this->dataset, 'UserFullName', 'UserFullName', 'Nom'),
                new FilterColumn($this->dataset, 'Mail', 'Mail', 'Mail'),
                new FilterColumn($this->dataset, 'Token', 'Token', 'Token'),
                new FilterColumn($this->dataset, 'UserSecretQestion', 'UserSecretQestion', 'Question de récupération'),
                new FilterColumn($this->dataset, 'UserSecretAnswer', 'UserSecretAnswer', 'Réponse de récupération'),
                new FilterColumn($this->dataset, 'UserStatus', 'UserStatus', 'Etat'),
                new FilterColumn($this->dataset, 'UserDescription', 'UserDescription', 'Description'),
                new FilterColumn($this->dataset, 'Roles', 'Roles', 'Roles')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['UserId'])
                ->addColumn($columns['UserName'])
                ->addColumn($columns['Mail'])
                ->addColumn($columns['UserStatus'])
                ->addColumn($columns['UserDescription'])
                ->addColumn($columns['Roles']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('UserStatus')
                ->setOptionsFor('Roles');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('userid_edit');
            
            $filterBuilder->addColumn(
                $columns['UserId'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('username_edit');
            $main_editor->SetMaxLength(100);
            $main_editor->SetPlaceholder('Ecrire votre identifiant d\'accès');
            
            $filterBuilder->addColumn(
                $columns['UserName'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('mail_edit');
            
            $filterBuilder->addColumn(
                $columns['Mail'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new ComboBox('userstatus_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->addChoice('0', 'actif');
            $main_editor->addChoice('1', 'inactif');
            $main_editor->addChoice('2', 'restitution du mot de pass');
            $main_editor->SetAllowNullValue(false);
            
            $multi_value_select_editor = new MultiValueSelect('UserStatus');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $text_editor = new TextEdit('UserStatus');
            
            $filterBuilder->addColumn(
                $columns['UserStatus'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('userdescription_edit');
            $main_editor->SetPlaceholder('Decrire l\'utilisateur (Optionel)');
            
            $filterBuilder->addColumn(
                $columns['UserDescription'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new MultiValueSelect('roles_edit');
            $main_editor->addChoice('Administrateur', 'Administrateur');
            $main_editor->addChoice('Manipulateur', 'Manipulateur');
            $main_editor->addChoice('Demandeur', 'Demandeur');
            $main_editor->addChoice('Patient', 'Patient');
            $main_editor->setMaxSelectionSize(0);
            
            $text_editor = new TextEdit('Roles');
            
            $filterBuilder->addColumn(
                $columns['Roles'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
            }
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for UserId field
            //
            $column = new NumberViewColumn('UserId', 'UserId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_UserName_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_UserFullName_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_Mail_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for UserStatus field
            //
            $column = new TextViewColumn('UserStatus', 'UserStatus', 'Etat', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_UserDescription_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_Roles_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for UserId field
            //
            $column = new NumberViewColumn('UserId', 'UserId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_UserName_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_UserFullName_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_Mail_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Token field
            //
            $column = new TextViewColumn('Token', 'Token', 'Token', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_Token_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UserStatus field
            //
            $column = new TextViewColumn('UserStatus', 'UserStatus', 'Etat', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_UserDescription_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_Roles_handler_view');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for UserName field
            //
            $editor = new TextEdit('username_edit');
            $editor->SetMaxLength(100);
            $editor->SetPlaceholder('Ecrire votre identifiant d\'accès');
            $editColumn = new CustomEditColumn('Pseudo', 'UserName', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for UserFullName field
            //
            $editor = new TextEdit('userfullname_edit');
            $editColumn = new CustomEditColumn('Nom', 'UserFullName', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Mail field
            //
            $editor = new TextEdit('mail_edit');
            $editColumn = new CustomEditColumn('Mail', 'Mail', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $validator = new EMailValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('EmailValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for UserStatus field
            //
            $editor = new ComboBox('userstatus_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('0', 'actif');
            $editor->addChoice('1', 'inactif');
            $editor->addChoice('2', 'restitution du mot de pass');
            $editColumn = new CustomEditColumn('Etat', 'UserStatus', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for UserDescription field
            //
            $editor = new TextEdit('userdescription_edit');
            $editor->SetPlaceholder('Decrire l\'utilisateur (Optionel)');
            $editColumn = new CustomEditColumn('Description', 'UserDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Roles field
            //
            $editor = new MultiValueSelect('roles_edit');
            $editor->addChoice('Administrateur', 'Administrateur');
            $editor->addChoice('Manipulateur', 'Manipulateur');
            $editor->addChoice('Demandeur', 'Demandeur');
            $editor->addChoice('Patient', 'Patient');
            $editor->setMaxSelectionSize(0);
            $editColumn = new CustomEditColumn('Roles', 'Roles', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for UserName field
            //
            $editor = new TextEdit('username_edit');
            $editor->SetMaxLength(100);
            $editor->SetPlaceholder('Ecrire votre identifiant d\'accès');
            $editColumn = new CustomEditColumn('Pseudo', 'UserName', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for UserPassword field
            //
            $editor = new TextEdit('userpassword_edit');
            $editor->SetPlaceholder('Ecrire un mot de passe fort pour votre sécurité');
            $editColumn = new CustomEditColumn('Mot de passe', 'UserPassword', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for UserFullName field
            //
            $editor = new TextEdit('userfullname_edit');
            $editColumn = new CustomEditColumn('Nom', 'UserFullName', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Mail field
            //
            $editor = new TextEdit('mail_edit');
            $editColumn = new CustomEditColumn('Mail', 'Mail', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $validator = new EMailValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('EmailValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for UserStatus field
            //
            $editor = new ComboBox('userstatus_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('0', 'actif');
            $editor->addChoice('1', 'inactif');
            $editor->addChoice('2', 'restitution du mot de pass');
            $editColumn = new CustomEditColumn('Etat', 'UserStatus', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->SetInsertDefaultValue('1');
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for UserDescription field
            //
            $editor = new TextEdit('userdescription_edit');
            $editor->SetPlaceholder('Decrire l\'utilisateur (Optionel)');
            $editColumn = new CustomEditColumn('Description', 'UserDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Roles field
            //
            $editor = new MultiValueSelect('roles_edit');
            $editor->addChoice('Administrateur', 'Administrateur');
            $editor->addChoice('Manipulateur', 'Manipulateur');
            $editor->addChoice('Demandeur', 'Demandeur');
            $editor->addChoice('Patient', 'Patient');
            $editor->setMaxSelectionSize(0);
            $editColumn = new CustomEditColumn('Roles', 'Roles', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for UserId field
            //
            $column = new NumberViewColumn('UserId', 'UserId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_UserName_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_Mail_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserStatus field
            //
            $column = new TextViewColumn('UserStatus', 'UserStatus', 'Etat', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_UserDescription_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_Roles_handler_print');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for UserId field
            //
            $column = new NumberViewColumn('UserId', 'UserId', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_UserName_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_Mail_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for UserStatus field
            //
            $column = new TextViewColumn('UserStatus', 'UserStatus', 'Etat', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_UserDescription_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_Roles_handler_export');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_UserName_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_Mail_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for UserStatus field
            //
            $column = new TextViewColumn('UserStatus', 'UserStatus', 'Etat', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_UserDescription_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('usersGrid_Roles_handler_compare');
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        public function OnGetFieldValue($fieldName, &$value)
        {
        if ($fieldName == 'UserDescription') {
            $value = nl2br(stripslashes(($value)));
            $value = preg_replace("/(<br>)+/", "<br>", $value);
        }
        
        if ($fieldName == 'UserFullName') {
            $value = nl2br(stripslashes(($value)));
            $value = preg_replace("/(<br>)+/", "<br>", $value);
        }
        
        if ($fieldName == 'UserSecretQestion') {
            $value = nl2br(stripslashes(($value)));
            $value = preg_replace("/(<br>)+/", "<br>", $value);
        }
         
        if ($fieldName == 'UserSecretAnswer') {
            $value = nl2br(stripslashes(($value)));
            $value = preg_replace("/(<br>)+/", "<br>", $value);
        }
        }
        
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','pdf'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','pdf'));
            $this->setDescription('Gestion de la liste des utilisateurs');
            $this->setDetailedDescription('Gestion de la liste des utilisateurs');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_UserName_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_UserFullName_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_Mail_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_UserDescription_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_Roles_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_UserName_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_Mail_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_UserDescription_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_Roles_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_UserName_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_Mail_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_UserDescription_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_Roles_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for UserName field
            //
            $column = new TextViewColumn('UserName', 'UserName', 'Pseudo', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_UserName_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserFullName field
            //
            $column = new TextViewColumn('UserFullName', 'UserFullName', 'Nom', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_UserFullName_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Mail field
            //
            $column = new TextViewColumn('Mail', 'Mail', 'Mail', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_Mail_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Token field
            //
            $column = new TextViewColumn('Token', 'Token', 'Token', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_Token_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for UserDescription field
            //
            $column = new TextViewColumn('UserDescription', 'UserDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_UserDescription_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for Roles field
            //
            $column = new TextViewColumn('Roles', 'Roles', 'Roles', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'usersGrid_Roles_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            $userToken = GenerateToken();
            $rowData['Token'] = $userToken;
            $rowData['UserPassword'] = md5($rowData['UserPassword']);
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doGetCustomUploadFileName($fieldName, $rowData, &$result, &$handled, $originalFileName, $originalFileExtension, $fileSize)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doGetCustomPagePermissions(Page $page, PermissionSet &$permissions, &$handled)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
    }

    SetUpUserAuthorization();

    try
    {
        $Page = new usersPage("users", "utilisateurs.php", GetCurrentUserPermissionSetForDataSource("users"), 'UTF-8');
        $Page->SetTitle('Utilisateurs');
        $Page->SetMenuLabel('Utilisateurs');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("users"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
