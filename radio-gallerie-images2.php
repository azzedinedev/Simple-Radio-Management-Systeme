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
    
    
    
    class radioresponsegalleryPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`radioresponsegallery`');
            $field = new IntegerField('Id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('ResponseId');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('ResponseGalleryImage');
            $this->dataset->AddField($field, false);
            $field = new StringField('ResponseGalleryDescription');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('ResponseId', '(SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId)', new StringField('Id'), new StringField('Id', 'ResponseId_Id', 'ResponseId_Id_Query Radio Response'), 'ResponseId_Id_Query Radio Response');
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
                new FilterColumn($this->dataset, 'Id', 'Id', 'Id'),
                new FilterColumn($this->dataset, 'ResponseId', 'ResponseId_Id', 'Rapport'),
                new FilterColumn($this->dataset, 'UpdateID', 'UpdateID', 'Update ID'),
                new FilterColumn($this->dataset, 'UpdateIP', 'UpdateIP', 'Update IP'),
                new FilterColumn($this->dataset, 'UpdateTime', 'UpdateTime', 'Update Time'),
                new FilterColumn($this->dataset, 'InsertID', 'InsertID', 'Insert ID'),
                new FilterColumn($this->dataset, 'InsertIP', 'InsertIP', 'Insert IP'),
                new FilterColumn($this->dataset, 'InsertTime', 'InsertTime', 'Insert Time'),
                new FilterColumn($this->dataset, 'ResponseGalleryImage', 'ResponseGalleryImage', 'Response Gallery Image'),
                new FilterColumn($this->dataset, 'ResponseGalleryDescription', 'ResponseGalleryDescription', 'Response Gallery Description')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['Id'])
                ->addColumn($columns['ResponseId'])
                ->addColumn($columns['ResponseGalleryImage'])
                ->addColumn($columns['ResponseGalleryDescription']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('ResponseId');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('id_edit');
            
            $filterBuilder->addColumn(
                $columns['Id'],
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
            
            $main_editor = new AutocompleteComboBox('responseid_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_ResponseId_Id_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('ResponseId', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_ResponseId_Id_search');
            
            $filterBuilder->addColumn(
                $columns['ResponseId'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('ResponseGalleryImage');
            
            $filterBuilder->addColumn(
                $columns['ResponseGalleryImage'],
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
            
            $main_editor = new TextEdit('ResponseGalleryDescription');
            
            $filterBuilder->addColumn(
                $columns['ResponseGalleryDescription'],
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
            // View column for Id field
            //
            $column = new NumberViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Id field
            //
            $column = new TextViewColumn('ResponseId', 'ResponseId_Id', 'Rapport', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new TextViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Response Gallery Image', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radioresponsegalleryGrid_ResponseGalleryImage_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Response Gallery Description', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radioresponsegalleryGrid_ResponseGalleryDescription_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new NumberViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Id field
            //
            $column = new TextViewColumn('ResponseId', 'ResponseId_Id', 'Rapport', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UpdateID field
            //
            $column = new NumberViewColumn('UpdateID', 'UpdateID', 'Update ID', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UpdateIP field
            //
            $column = new TextViewColumn('UpdateIP', 'UpdateIP', 'Update IP', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for UpdateTime field
            //
            $column = new DateTimeViewColumn('UpdateTime', 'UpdateTime', 'Update Time', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for InsertID field
            //
            $column = new NumberViewColumn('InsertID', 'InsertID', 'Insert ID', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for InsertIP field
            //
            $column = new TextViewColumn('InsertIP', 'InsertIP', 'Insert IP', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for InsertTime field
            //
            $column = new DateTimeViewColumn('InsertTime', 'InsertTime', 'Insert Time', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new TextViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Response Gallery Image', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radioresponsegalleryGrid_ResponseGalleryImage_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Response Gallery Description', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radioresponsegalleryGrid_ResponseGalleryDescription_handler_view');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for ResponseId field
            //
            $editor = new AutocompleteComboBox('responseid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Type');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Id', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Rapport', 'ResponseId', 'ResponseId_Id', 'edit_ResponseId_Id_search', $editor, $this->dataset, $lookupDataset, 'Id', 'Id', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ResponseGalleryImage field
            //
            $editor = new TextAreaEdit('responsegalleryimage_edit', 50, 8);
            $editColumn = new CustomEditColumn('Response Gallery Image', 'ResponseGalleryImage', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ResponseGalleryDescription field
            //
            $editor = new TextAreaEdit('responsegallerydescription_edit', 50, 8);
            $editColumn = new CustomEditColumn('Response Gallery Description', 'ResponseGalleryDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for ResponseId field
            //
            $editor = new AutocompleteComboBox('responseid_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Type');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Id', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Rapport', 'ResponseId', 'ResponseId_Id', 'insert_ResponseId_Id_search', $editor, $this->dataset, $lookupDataset, 'Id', 'Id', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ResponseGalleryImage field
            //
            $editor = new TextAreaEdit('responsegalleryimage_edit', 50, 8);
            $editColumn = new CustomEditColumn('Response Gallery Image', 'ResponseGalleryImage', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ResponseGalleryDescription field
            //
            $editor = new TextAreaEdit('responsegallerydescription_edit', 50, 8);
            $editColumn = new CustomEditColumn('Response Gallery Description', 'ResponseGalleryDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new NumberViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Id field
            //
            $column = new TextViewColumn('ResponseId', 'ResponseId_Id', 'Rapport', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new TextViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Response Gallery Image', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radioresponsegalleryGrid_ResponseGalleryImage_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Response Gallery Description', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radioresponsegalleryGrid_ResponseGalleryDescription_handler_print');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new NumberViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(' ');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for Id field
            //
            $column = new TextViewColumn('ResponseId', 'ResponseId_Id', 'Rapport', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new TextViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Response Gallery Image', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radioresponsegalleryGrid_ResponseGalleryImage_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Response Gallery Description', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radioresponsegalleryGrid_ResponseGalleryDescription_handler_export');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new TextViewColumn('ResponseId', 'ResponseId_Id', 'Rapport', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new TextViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Response Gallery Image', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radioresponsegalleryGrid_ResponseGalleryImage_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Response Gallery Description', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('radioresponsegalleryGrid_ResponseGalleryDescription_handler_compare');
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
            $this->setDescription('Images des films radiologiques annéxées au rapport radio');
            $this->setDetailedDescription('Images des films radiologiques annéxées au rapport radio');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            //
            // View column for ResponseGalleryImage field
            //
            $column = new TextViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Response Gallery Image', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'radioresponsegalleryGrid_ResponseGalleryImage_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Response Gallery Description', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'radioresponsegalleryGrid_ResponseGalleryDescription_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseGalleryImage field
            //
            $column = new TextViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Response Gallery Image', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'radioresponsegalleryGrid_ResponseGalleryImage_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Response Gallery Description', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'radioresponsegalleryGrid_ResponseGalleryDescription_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseGalleryImage field
            //
            $column = new TextViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Response Gallery Image', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'radioresponsegalleryGrid_ResponseGalleryImage_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Response Gallery Description', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'radioresponsegalleryGrid_ResponseGalleryDescription_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Type');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Id', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_ResponseId_Id_search', 'Id', 'Id', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            $selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Type');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Id', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_ResponseId_Id_search', 'Id', 'Id', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Type');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Id', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_ResponseId_Id_search', 'Id', 'Id', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Type');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Id', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_ResponseId_Id_search', 'Id', 'Id', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseGalleryImage field
            //
            $column = new TextViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Response Gallery Image', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'radioresponsegalleryGrid_ResponseGalleryImage_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Response Gallery Description', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'radioresponsegalleryGrid_ResponseGalleryDescription_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);$selectQuery = 'SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId';
            $insertQuery = array('INSERT INTO radioresponse (
            `ResponseUserId` ,
            `RequestId` ,
            `ResponseDateTime` ,
            `ResponseImage`,
            `ResponseDescription` ,
            `Conclusion` ,
            `Type` ,
            `Hauteur` ,
            `Largeur` ,
            `Resolution` ,
            `Status`,
            `UpdateID` ,
            `UpdateIP` ,
            `UpdateTime` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES (
            :ResponseUserId, :RequestId, :ResponseDateTime, :ResponseImage, :ResponseDescription, :Conclusion, :Type, :Hauteur, :Largeur, :Resolution, :Status, :InsertID, :InsertIP, :InsertTime, :InsertID, :InsertIP, :InsertTime
            )');
            $updateQuery = array('UPDATE radioresponse SET  
            `ResponseUserId` = :ResponseUserId ,
            `RequestId` = :RequestId ,
            `ResponseDateTime` = :ResponseDateTime ,
            `ResponseImage` = :ResponseImage,
            `ResponseDescription` = :ResponseDescription,
            `Conclusion` = :Conclusion,
            `Type` = :Type,
            `Hauteur` = :Hauteur,
            `Largeur` = :Largeur,
            `Resolution` = :Resolution,
            `Status` = :Status,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponse  
            WHERE Id = :Id');
            $lookupDataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Radio Response');
            $field = new StringField('Id');
            $lookupDataset->AddField($field, true);
            $field = new StringField('ResponseUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseImage');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Conclusion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Type');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Hauteur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Largeur');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('Resolution');
            $lookupDataset->AddField($field, false);
            $field = new StringField('Status');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $lookupDataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestUserId');
            $lookupDataset->AddField($field, false);
            $field = new StringField('PatientId');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $lookupDataset->AddField($field, false);
            $field = new StringField('RequestStatus');
            $lookupDataset->AddField($field, false);
            $field = new StringField('IPlusTime');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('Id', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_ResponseId_Id_search', 'Id', 'Id', null, 20);
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
        $Page = new radioresponsegalleryPage("radioresponsegallery", "radio-gallerie-images2.php", GetCurrentUserPermissionSetForDataSource("radioresponsegallery"), 'UTF-8');
        $Page->SetTitle('Album films radio');
        $Page->SetMenuLabel('Album films radio');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("radioresponsegallery"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
