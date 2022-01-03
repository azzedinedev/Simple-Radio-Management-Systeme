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
    
    
    
    class Query_Gallery_with_Response_and_RequestPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $selectQuery = 'SELECT RRG.*, RResp.ResponseDateTime, RResp.ResponseDescription, RReq.PatientId, RReq.RequestDateTime, RReq.RequestDescription FROM radioresponsegallery AS RRG  
            INNER JOIN radioresponse AS RResp ON RRG.ResponseId = RResp.Id 
            INNER JOIN radiorequests AS RReq  ON RResp.RequestId = RReq.Id 
            WHERE RResp.STATUS = \'reponse\'';
            $insertQuery = array('INSERT INTO radioresponsegallery (
            `ResponseId` ,
            `ResponseGalleryImage` ,
            `ResponseGalleryDescription` ,
            `InsertID` ,
            `InsertIP` ,
            `InsertTime`
            )
            VALUES ( :ResponseId, :ResponseGalleryImage, :ResponseGalleryDescription, :InsertID, :InsertIP, :InsertTime )');
            $updateQuery = array('UPDATE radioresponsegallery SET  
            `ResponseId` = :ResponseId ,
            `ResponseGalleryImage` = :ResponseGalleryImage ,
            `ResponseGalleryDescription` = :ResponseGalleryDescription ,
            `UpdateID` = :UpdateID,
            `UpdateIP` = :UpdateIP ,
            `UpdateTime` = :UpdateTime
            WHERE Id = :Id');
            $deleteQuery = array('DELETE FROM radioresponsegallery WHERE Id = :Id');
            $this->dataset = new QueryDataset(
              MySqlIConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Query Gallery with Response and Request');
            $field = new StringField('Id');
            $this->dataset->AddField($field, true);
            $field = new StringField('ResponseId');
            $this->dataset->AddField($field, false);
            $field = new StringField('ResponseGalleryImage');
            $this->dataset->AddField($field, false);
            $field = new StringField('ResponseGalleryDescription');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('UpdateID');
            $this->dataset->AddField($field, false);
            $field = new StringField('UpdateIP');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('UpdateTime');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('InsertID');
            $this->dataset->AddField($field, false);
            $field = new StringField('InsertIP');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('InsertTime');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('ResponseDateTime');
            $this->dataset->AddField($field, false);
            $field = new StringField('ResponseDescription');
            $this->dataset->AddField($field, false);
            $field = new StringField('PatientId');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('RequestDateTime');
            $this->dataset->AddField($field, false);
            $field = new StringField('RequestDescription');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('ResponseId', '(SELECT TResp.*,  
            TReq.RequestUserId,  
            TReq.PatientId , 
            TReq.RequestDateTime , 
            TReq.RequestDescription , 
            TReq.Status AS RequestStatus , 
            CONCAT(TReq.Id, \' (\', TReq.RequestDateTime,\')\') AS IPlusTime 
            FROM radioresponse AS TResp 
            INNER JOIN radiorequests AS TReq ON TReq.Id = TResp.RequestId)', new StringField('Id'), new StringField('IPlusTime', 'ResponseId_IPlusTime', 'ResponseId_IPlusTime_Query Radio Response'), 'ResponseId_IPlusTime_Query Radio Response');
            $this->dataset->AddLookupField('PatientId', '(SELECT P.*, CONCAT(P.PatientName, \' (\', P.PatientAge,\')\') AS NamePlusAge FROM patients AS P)', new StringField('PatientId'), new StringField('NamePlusAge', 'PatientId_NamePlusAge', 'PatientId_NamePlusAge_Query Patient Details'), 'PatientId_NamePlusAge_Query Patient Details');
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
                new FilterColumn($this->dataset, 'ResponseId', 'ResponseId_IPlusTime', 'Rapport Id'),
                new FilterColumn($this->dataset, 'ResponseGalleryImage', 'ResponseGalleryImage', 'Image'),
                new FilterColumn($this->dataset, 'ResponseGalleryDescription', 'ResponseGalleryDescription', 'Description'),
                new FilterColumn($this->dataset, 'UpdateID', 'UpdateID', 'Update ID'),
                new FilterColumn($this->dataset, 'UpdateIP', 'UpdateIP', 'Update IP'),
                new FilterColumn($this->dataset, 'UpdateTime', 'UpdateTime', 'Update Time'),
                new FilterColumn($this->dataset, 'InsertID', 'InsertID', 'Insert ID'),
                new FilterColumn($this->dataset, 'InsertIP', 'InsertIP', 'Insert IP'),
                new FilterColumn($this->dataset, 'InsertTime', 'InsertTime', 'Insert Time'),
                new FilterColumn($this->dataset, 'ResponseDateTime', 'ResponseDateTime', 'Date du rapport'),
                new FilterColumn($this->dataset, 'ResponseDescription', 'ResponseDescription', 'Rapport'),
                new FilterColumn($this->dataset, 'PatientId', 'PatientId_NamePlusAge', 'Patient'),
                new FilterColumn($this->dataset, 'RequestDateTime', 'RequestDateTime', 'Date de la demande'),
                new FilterColumn($this->dataset, 'RequestDescription', 'RequestDescription', 'Demande')
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
            
            $main_editor = new AutocompleteComboBox('responseid_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_ResponseId_IPlusTime_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('ResponseId', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_ResponseId_IPlusTime_search');
            
            $text_editor = new TextEdit('ResponseId');
            
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
            
            $main_editor = new TextEdit('responsegallerydescription_edit');
            
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
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for IPlusTime field
            //
            $column = new TextViewColumn('ResponseId', 'ResponseId_IPlusTime', 'Rapport Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new ExternalImageViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
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
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for IPlusTime field
            //
            $column = new TextViewColumn('ResponseId', 'ResponseId_IPlusTime', 'Rapport Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new ExternalImageViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Description', $this->dataset);
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
            // View column for ResponseDateTime field
            //
            $column = new DateTimeViewColumn('ResponseDateTime', 'ResponseDateTime', 'Date du rapport', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Rapport', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Gallery_with_Response_and_RequestGrid_ResponseDescription_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for NamePlusAge field
            //
            $column = new TextViewColumn('PatientId', 'PatientId_NamePlusAge', 'Patient', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for RequestDateTime field
            //
            $column = new DateTimeViewColumn('RequestDateTime', 'RequestDateTime', 'Date de la demande', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Demande', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('Query_Gallery_with_Response_and_RequestGrid_RequestDescription_handler_view');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for Id field
            //
            $editor = new TextEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'Id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
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
            $lookupDataset->setOrderByField('IPlusTime', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Rapport Id', 'ResponseId', 'ResponseId_IPlusTime', 'edit_ResponseId_IPlusTime_search', $editor, $this->dataset, $lookupDataset, 'Id', 'IPlusTime', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ResponseGalleryImage field
            //
            $editor = new ImageUploader('responsegalleryimage_edit');
            $editor->SetShowImage(true);
            $editor->setAcceptableFileTypes('image/*');
            $editColumn = new UploadFileToFolderColumn('Image', 'ResponseGalleryImage', $editor, $this->dataset, false, false, 'patients/patient_%PatientId%/radio_%ResponseId%', '%original_file_name%', $this->OnGetCustomUploadFilename, false);
            $editColumn->SetReplaceUploadedFileIfExist(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ResponseGalleryDescription field
            //
            $editor = new TextEdit('responsegallerydescription_edit');
            $editColumn = new CustomEditColumn('Description', 'ResponseGalleryDescription', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for Id field
            //
            $editor = new TextEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'Id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
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
            $lookupDataset->setOrderByField('IPlusTime', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Rapport Id', 'ResponseId', 'ResponseId_IPlusTime', 'insert_ResponseId_IPlusTime_search', $editor, $this->dataset, $lookupDataset, 'Id', 'IPlusTime', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ResponseGalleryImage field
            //
            $editor = new ImageUploader('responsegalleryimage_edit');
            $editor->SetShowImage(true);
            $editor->setAcceptableFileTypes('image/*');
            $editColumn = new UploadFileToFolderColumn('Image', 'ResponseGalleryImage', $editor, $this->dataset, false, false, 'patients/patient_%PatientId%/radio_%ResponseId%', '%original_file_name%', $this->OnGetCustomUploadFilename, false);
            $editColumn->SetReplaceUploadedFileIfExist(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ResponseGalleryDescription field
            //
            $editor = new TextEdit('responsegallerydescription_edit');
            $editColumn = new CustomEditColumn('Description', 'ResponseGalleryDescription', $editor, $this->dataset);
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
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for IPlusTime field
            //
            $column = new TextViewColumn('ResponseId', 'ResponseId_IPlusTime', 'Rapport Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new ExternalImageViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $grid->AddPrintColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for IPlusTime field
            //
            $column = new TextViewColumn('ResponseId', 'ResponseId_IPlusTime', 'Rapport Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new ExternalImageViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $grid->AddExportColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for Id field
            //
            $column = new TextViewColumn('Id', 'Id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for IPlusTime field
            //
            $column = new TextViewColumn('ResponseId', 'ResponseId_IPlusTime', 'Rapport Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for ResponseGalleryImage field
            //
            $column = new ExternalImageViewColumn('ResponseGalleryImage', 'ResponseGalleryImage', 'Image', $this->dataset);
            $column->setImageHintTemplate('');
            $column->setNullLabel('');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $column->setHeight('100');
            $grid->AddCompareColumn($column);
            
            //
            // View column for ResponseGalleryDescription field
            //
            $column = new TextViewColumn('ResponseGalleryDescription', 'ResponseGalleryDescription', 'Description', $this->dataset);
            $column->setNullLabel('');
            $column->SetOrderable(true);
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
            $lookupDataset->setOrderByField('IPlusTime', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_ResponseId_IPlusTime_search', 'Id', 'IPlusTime', null, 20);
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
            $lookupDataset->setOrderByField('IPlusTime', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_ResponseId_IPlusTime_search', 'Id', 'IPlusTime', null, 20);
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
            $lookupDataset->setOrderByField('IPlusTime', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_ResponseId_IPlusTime_search', 'Id', 'IPlusTime', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for ResponseDescription field
            //
            $column = new TextViewColumn('ResponseDescription', 'ResponseDescription', 'Rapport', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Gallery_with_Response_and_RequestGrid_ResponseDescription_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for RequestDescription field
            //
            $column = new TextViewColumn('RequestDescription', 'RequestDescription', 'Demande', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'Query_Gallery_with_Response_and_RequestGrid_RequestDescription_handler_view', $column);
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
            $lookupDataset->setOrderByField('IPlusTime', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_ResponseId_IPlusTime_search', 'Id', 'IPlusTime', null, 20);
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
            $rowData['InsertIP'] = $_SERVER['SERVER_ADDR'];
            $rowData['InsertTime'] = SMDateTime::Now();
            $rowData['InsertID'] = $page->GetCurrentUserId();
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            $rowData['UpdateIP'] = $_SERVER['SERVER_ADDR'];
            $rowData['UpdateTime'] = SMDateTime::Now();
            $rowData['UpdateID'] = $page->GetCurrentUserId();
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
            if ($fieldName === 'ResponseGalleryImage') {
            
                $newFileName = "Film_gallery_" . date('Y-m-d_H-i-s') ."_PT" . $rowData['PatientId'] ."_RD". $rowData['ResponseId'] . "_" . $originalFileExtension;
            
                $result = str_replace($originalFileName, $newFileName, $result);
            
                $handled = true;
            
            }
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
        $Page = new Query_Gallery_with_Response_and_RequestPage("Query_Gallery_with_Response_and_Request", "radio-gallerie-images.php", GetCurrentUserPermissionSetForDataSource("Query Gallery with Response and Request"), 'UTF-8');
        $Page->SetTitle('Album films radio');
        $Page->SetMenuLabel('Album films radio');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("Query Gallery with Response and Request"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
