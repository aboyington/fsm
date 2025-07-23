A **work order** is a record created for executing a contact service request.  

  
## Create Work Orders

![Info](https://img.zohostatic.com/zde/static/images/info.png)

**Permission Required**: [Work Orders](https://help.zoho.com/portal/en/kb/fsm/set-up-workforce/articles/profiles-and-permissions#Basic_Permissions)

  

A Work Order can be created in one of the following ways:  

1. **From Requests**: Use the **Convert to Work Order** option to create a work order for a request. This option will display the _Create Work Order_ page with the field values pre-filled. Use the **Requests to Work Order** mapping to determine the Request field values that you want to pre-fill the fields in the _Create Work Order_ page with. Refer to [this](https://help.zoho.com/portal/en/kb/zohofsm/work-order-automation/articles/work-order-setup#Request_to_Work_Order_Mapping) page for details.  
      
    
2. **From Estimates**: Use the **Convert to Work Order** option to create a work order for an estimate. This option will display the _Create Work Order_ page with field values pre-filled. Use the **Estimate to Work Order** mapping to determine the Estimate field values that you want to pre-fill the fields in the _Create Work Order_ page with. Refer to [this](https://help.zoho.com/portal/en/kb/zohofsm/work-order-automation/articles/work-order-setup#Estimate_to_Work_Order_Mapping) page for details.  
      
    
3. **From contact phone Requests**: The Customer Service Agent creates work orders for the contact requests received through phone calls.  
    

To create a work order:  

1. Select **Work Orders** from the **Work Order Management** menu and click **Create**.  
    
2. Enter the following details, then click **Save**:  
    

3. A **Summary** of the work order  
    
4. A **Priority**  
    
5. A **Type** to indicate the nature of the service the work order is being created for.  
    
    Apart from the default values, you can also [add](https://help.zoho.com/portal/en/kb/fsm/customize-field-services/articles/customize-standard-modules#Pick_List) custom values to this field.
    
6. A **Due Date** by which the work order should be closed  
    
7. The **Contact** for whom the work order is being created   
    
8. The **Company** the contact belongs to.  
    Conversely, for a given company you can choose the contacts associated with it.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn93e7547555a81017d53da39325ec9d4f31caf2b67f9d29808802b852340cd151b8dbacc7cf7d31f93eea9369fce375c4?inline=true)  
      
    
9. The **Phone** number of the contact  
    
10. The **Email** address of the contact  
    If there is no email address associated with the contact, the email address of the company (if present) will be used.
    
11. An **Asset  
    **An [Asset](https://help.zoho.com/portal/en/kb/fsm/assets/articles/manage-assets) is added when the service is for a product that you have sold.
    
12. The **Territory** in which the contact is located  
    
13. A **Service Address**   
    
    This is the address where the service needs to be carried out. The service address present for the Asset, Company, or Contact will be used, in this order of preference. You can choose any other available address or click **Create New**.
    
    Upon clicking **Create New**, the _Add Address_ overlay will be displayed. To add a new address, enter the details and click **Save**. You can either choose to add an address to the Company/Contact or create a Single Use Address. The Single Use Address can be used only in the current Work Order record.
    
14. A **Billing Address  
      
    ![](https://help.zoho.com/galleryDocuments/edbsncd2ac29fa7505370e8f04b962f1d14b8c67e062c511d8f823117d06d0df70ffe4f6b3910e1eee1ab9040512c506e6be2?inline=true)  
      
    **
15. **Preferred Date1**, and **Preferred Date2** for the service call
16. A **Preferred Time**.
    
17. Any additional **Preference Note** regarding the service call
18. Select a **Currency**.  
    The Currency and Exchange Rate will be displayed only if [multiple currencies](https://help.zoho.com/portal/en/kb/fsm/getting-started/articles/organization-setting#Manage_multiple_currencies) are enabled.
19. Entries for **Services**  
    
    These denote the services which have to be delivered for this work order. The Services added in [Services And Parts](https://help.zoho.com/portal/en/kb/fsm/services-and-parts/articles/manage-services-and-parts) module will be listed here. You can [search](https://help.zoho.com/portal/en/kb/fsm/manage-fsm-data/articles/search-record#Advanced_Lookup_Search) a service using its SKU. If only a single service is added, then it will by default get associated with the Parts, and Skills that you add. [Service tasks](https://help.zoho.com/portal/en/kb/fsm/work-order-management/articles/service-tasks#Use_Service_Tasks_in_a_Work_Order), if present for the service will also get added.   
    
    Select a Tax Rate or choose non-taxable by providing a reason. Click [here](https://help.zoho.com/portal/en/kb/fsm/billing/articles/tax-setting#Taxes) to find details about setting the tax rates. The default tax preferences will be set based on the sync settings.   
    Enter a percentage for the discount you wish to offer or a discount amount. You can choose whether to offer a discount at the line item level or the transaction level. Log in to Zoho Books/Invoice and navigate to **Settings** > **Preferences** > **General** > **Do you give discounts?** and choose the desired option.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn9ab47de3d55744ead4abe0afb7344f011c20fbf0f2293aa1671f99f0e7d686cb363e275168edef8dee722c9a73d8c024?inline=true)  
      
    
20. Add the **Parts** necessary for the services.  
    
    Select the **Service** for which the Part has been added. You can [search](https://help.zoho.com/portal/en/kb/fsm/manage-fsm-data/articles/search-record#Advanced_Lookup_Search) a part using its SKU. Select a Tax Rate or choose non-taxable by providing a reason. Enter a percentage for the discount you wish to offer or a discount amount.
    
21. Add the **Skills** necessary for the services.  
    
    Select the **Service** for which the Skill has been added.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn99f98a0f0a6a50acf0a7c7ec7fdd3f62b5170844331979d0b94ceabee2d7e7b67a8caef2bae4b8a1de66bcd669a74ece?inline=true)  
    

![Info](https://img.zohostatic.com/zde/static/images/info.png)

**Skills**: These denote the professional capabilities needed for the work order.  

**Parts**: These denote the products which are required for carrying out this work order.

  

In the _Work Order Details_ page, you will have the links to all its related records, such as Request, Estimate, and Appointments. A history of the activities on the record can be found under the tab **[Timeline](https://help.zoho.com/portal/en/kb/fsm/data-administration/audit-log/articles/timeline)**.

  

![](https://help.zoho.com/galleryDocuments/edbsn47fc32a1ae832d3bc833796bb739f3b50f2d673258cb00484db93d80dad108f0395f542b3145923d9e1b40bec107a19f?inline=true)

  

**Note**: The monthly limit for work orders will be twice the number of service appointments you [purchase](https://help.zoho.com/portal/en/kb/fsm/getting-started/articles/fsm-pricing#Volume_based_pricing). If needed, we can increase this limit for you.

### Create Follow-up Work Orders and Estimates  

You can create a follow-up work order or estimate for an work order. If the agent finds additional work that needs to be completed during the initial work order, a follow-up work order can be created. A follow-up work order can also be created for routine maintenance on equipment, which can help prevent breakdowns and extend the lifespan of the equipment. Similarly, a follow-up estimate can be created to provide the customer with an accurate cost estimate for additional work or parts required for the original work order.  

  

To create a follow-up work order or estimate:  

1. Click the **Related List** tab of the work order for which you want to create the follow-up work order or estimate.  
    
2. Click **Create** for **Follow Up Work Orders** or **Follow Up Estimates**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsncbda14867ad4a812a98a831e8223b2c886a01aa7f2e36eff54f5430568709cd0b2be76d5fdd65b6e8587e191086450a2?inline=true)

The created follow-up work order or estimate will be listed respectively under **Follow Up Work Orders** and **Follow Up Estimates**.  

  

![](https://help.zoho.com/galleryDocuments/edbsn573009c4753ea25338bb4327087815f9d2141d32a21e618924d7bea796855c4e21b74cb3054f1f8db75c0404c616a1da?inline=true)  

  

In the follow up work order, the work order from which it was created will be listed as the **Parent Work Order**.  

  

![](https://help.zoho.com/galleryDocuments/edbsn2ddcf8f02c97e8920ab874b3b40f0b575ebf41943bca3d23366c95259e9ed7cc50f7ffe6e83d0c9d4d9f04cc3e2d98bf?inline=true)  

  

Click [here](https://help.zoho.com/portal/en/kb/fsm/mobilize-your-workforce/articles/fsm-mobile-app#Follow-up_Work_Order_and_Estimate) to know how to create follow-up work order or estimate from the mobile app.

## Edit Work Orders  

To edit work orders:  

1. Select **Work Orders** from the **Work Order Management** menu.  
    
2. Click the work order you want to edit. In the _Work Order details_ page, click **Edit**.  
    
3. Click **Save** after making the changes.   
    

### Managing Addresses  

You can perform the following actions on an address, Service or Billing, in the _Edit Work Order_ overlay:  

1. **Choose another address** : If there are multiple addresses present, then click the **Choose another** icon [ ![](https://help.zoho.com/galleryDocuments/edbsn33130466bd80631bfcb5146b2f9b867f698ec979b186845ab8f0a7d253bccb20b8234fcc6252b879c2eef26d2c681333?inline=true) ] to select another address. In the _Select Billing/Service Address_ pop-up, select the desired address.  
      
    
    ![](https://help.zoho.com/galleryDocuments/edbsn9c7c903507d50d56168fd55aec31d8219b05fc746625a3c2ca6b1ec3982663bec3892fc45be2dbdf3118b7cfa0453c7b?inline=true)  
    
      
    
2. **Edit address**: To make changes to the Service/Billing address, click the **Edit** icon [ ![](https://help.zoho.com/galleryDocuments/edbsnfad17b6dbb09c0379f762cb1343a6856ebfc4bb0403007c42385a0864593fd91d27487abedf43111b84d8b60104466df?inline=true)]. In the _Edit Address_ overlay make the necessary changes and click **Save**.  
    If the checkbox **Also update this address in the <Parent> module** is selected, then the modified address will be updated in the parent (Company or Contact) module too.
    
      
    
    ![](https://help.zoho.com/galleryDocuments/edbsn4db5535cc4409b1cb90285da8c270c6501cd148ece2759aa5f567c0197808f62dfe21603205366a3106122b6d1288c88?inline=true)  
    
      
    
3. **Add address**: Multiple addresses can be added to a contact. Click the **Add** icon [![](https://help.zoho.com/galleryDocuments/edbsnc1f484037eb31204fbbe5817df38b26c5830016dcfda925f8d9aab6993e6a53f74298b6502b6d92f9472ade7c6f41a0a?inline=true)]. In the _Add Address_ overlay, enter the details and click **Save**.

## Delete Work Orders  

You can either delete work orders individually or in bulk.  

  

To delete a work order:  

1. Select **Work Orders** from the **Work Order Management** menu.  
    
2. Click the work order you want to delete. In the _Work Order details_ page, click **Delete**.  
    
3. Click **Delete** in the _Confirm Delete_ popup.

![](https://help.zoho.com/galleryDocuments/edbsn221e83cd8b0d4a6d56623b0caa9d1b3bfc316714bc64c530567de27f1fb3e9c0ff4ff605be69d90e03b32ceb5b3018a6?inline=true)  

  

To delete work orders in bulk:  

1. In the _Work Orders List_ page, select the work orders you want to delete or select the master checkbox to select all records and click **Delete**.  
    
2. Click **Delete** in the _Confirm Delete_ popup.

![](https://help.zoho.com/galleryDocuments/edbsn979b802ef13827f982ce062257513b2db0466eea85360b18dc48f25e41173525498213cc8bb3ead386f0675229dcd054?inline=true)  

## Clone Work Order  

You can easily create a work order by replicating the details of another work order using the cloning feature. To clone a work order:

1. Select **Work Orders** from the **Work Order Management** menu.  
    
2. Click the work order you want to clone and in the _Work Order details_ page, click **Clone**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsne59a837c1bc0488acbc5c08812f50a4c31c4b40fd9d5f61b174033c46ac1ec0636a62214466602ae29fc8bda35dd9c49?inline=true)  
      
    
3. Click **Save** after making any changes.  
    All the details from the source work order will be prefilled.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsnb78c78f0e8e6cbe6adc44900d02a18a647d970675931af746e4aa5808108f2af8da4c80e71a742f552ba707276e6bbbb?inline=true)  
    

## Change Owner  

By default, the **Super Admin** is the owner of all the work orders. You can assign another user as the owner of a work order by following these steps:

1. Select **Work Orders** from the **Work Order Management** menu.  
    
2. Click the work order whose owner you want to change and in the _Work Order details_ page, click **Change Owner**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn02bc754220e450c2828745d261dc454d4ca9419bfde54f26c67888252d1e1ebb586cda5d35a057e68ab027a8b0449fa9?inline=true)  
      
    
3. Select the user you want to be the owner and click **Save**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn9cf309b2feb3e99664681823baf75f2002892232271e7df39f44fca484ab0451828bde632d79358ab81af4b306f81596?inline=true)  
    

## Complete Work Orders  

A work order can be completed only if service appointments have been created for all the associated services and all these service appointments have been completed. When a work order is completed, the status of the work order changes to **Completed**. You can also [automate](https://help.zoho.com/portal/en/kb/fsm/field-service-setting/articles/configure-field-service-process#Automate_Work_Order_Completion) work order completion.

  

To complete a work order:  

1. Select **Work Orders** from the **Work Order Management** menu.  
    
2. Click the work order you want to complete and in the _Work Order details_ page, click **Complete Work**.  
    

![](https://help.zoho.com/galleryDocuments/edbsnd01d8a078f25d8c5402ace2b6fa9f4c1d56763f24f71e4d53100f325988e5b91ddabb6b22d06dd1575579f9726644cf1?inline=true)  

  

You can also complete a work order when you try to complete a service appointment.  

  

![](https://help.zoho.com/galleryDocuments/edbsn7dc4b53e9f9ce519c1e3190e495264d82b47636c2348debf286b426d450bda3bbf906e6474ef40f1530564d5e27e4066?inline=true)  

  

You can also force complete a work order even when some of its line items are only partially completed.  

  

![](https://help.zoho.com/galleryDocuments/edbsnb9b201aa5fc5f7f781ad4cd72aa3080b44166e80ad0eb416685ac07740c97372c2218761eb6fd8f377b9157571e5382f?inline=true)  

## Close Work Orders  

When all the activities related to a work order have been completed, then the work order can be closed. When a work order is closed, the status of the work order changes to  **Closed**.

  

To close a work order:  

1. Select **Work Orders** from the **Work Order Management** menu.  
    
2. Click the work order you want to close and in the _Work Order details_ page, click **Close**.  
    

![](https://help.zoho.com/galleryDocuments/edbsn35a3ceff65f031624cc9a4ca6cfa87c0c619bd61ff0a10094a5e2d63d368aaddd152d80d51025ac1b188a0bb900ae07c?inline=true)

## Cancel Work Orders  

There may be instances when you do not want to proceed with a work order. For example, customer who had requested for an AC installation decides to not go ahead with the installation for the time being. In this case, the work order created for the this request will have to be cancelled.  

  

When a work order is cancelled, the following changes occur:

1. the status of the work order changes to **Cancelled**.  
    
2. the appointments created for the work order will also be automatically cancelled.

To cancel a work order:  

1. Select **Work Orders** from the **Work Order Management** menu.  
    
2. Click the work order you want to cancel and in the _Work Order details_ page, click **Cancel**.  
    
3. Click **Save** in the confirmation message.

![](https://help.zoho.com/galleryDocuments/edbsn63d0d7b44398146f21d427160eb0aaf4d25fe1aaa70019f3ed89cec8b9da84240a7d02e65090f344a9dbbe43f1bb3d8a?inline=true)  

## Terminate Work Orders  

There may be instances when you cannot proceed with a work order. For example, the field agent goes to the service location and finds out that the customer is not present at the service location, or the field agent is unable to carry out their task due to faulty equipment. In these cases, the work order created for the this request will have to be terminated.

  

When a work order is terminated, the following changes occur:

1. The status of the work order changes to **Cannot Complete**.  
    
2. The status of the appointments created for the work order will automatically change to **Cannot Complete**.

To terminate a work order:

1. Select **Work Orders** from the **Work Order Management** menu.  
    
2. Click the work order you want to terminate. In the _Work Order details_ page, click **Terminate**.  
    
3. Click **Save** in the confirmation message.

![](https://help.zoho.com/galleryDocuments/edbsn373a696114e9a7435b762b4dad49f297b27a974f792ef2038edfdd4ba4f2033d9f2cd9fa9726b3c8890e075d6777e18b?inline=true)  

## Mark Billing Status As Non Billable  

There are work orders that you undertake but do not charge clients for. These work orders are typically associated with internal activities that are necessary for the operation of the business but do not directly generate revenue. Common examples include employee training, internal meetings, administrative tasks, etc.  

   

You can mark the billing status of these work orders as Non Billable. After you do so, you will not be able to create invoices for these work orders.  

  

To mark the billing status of a work order as non billable:  

1. Select **Work Orders** from the **Work Order Management** menu.  
    
2. Click the desired work order. In the _Work Order details_ page, click **Non Billable**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn72b54a8327d8a59562e4ae805d0c2a65a6d8b1c8636bdce913ffd118cc934b5967c4fca3376dd86a1efc51432813fed4?inline=true)  
      
    
3. Click **Proceed** in the confirmation message.  
    
4. Enter a reason for marking the billing status of the work order as non billable and click **Continue**.  
    

## Mark Billing Status As Void 

If there are work orders that are rendered invalid after they were created but you do not want to delete them, then you can mark their billing status as Void. After you do so, the status of the invoices generated for these work orders will be **Void**.   

  

To mark the billing status of a work order as void:  

1. Select **Work Orders** from the **Work Order Management** menu.  
    
2. Click the desired work order. In the _Work Order details_ page, click **Void**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn149ac4941733f82c2e200b603ca8444bafd9a8cf1decfaf6debdad4c15f5515dc6394474f9fb7e8e250d03d663fca40b?inline=true)  
      
    
3. Click **Proceed** in the confirmation message.  
    
4. Enter a reason for marking the billing status of the work order as void and click **Continue**.  
    

## Create Service Appointments     

Service appointments are jobs created for carrying out the services in a work order. These service appointments are assigned to field agents or crew who will then render the services at the contact location. You can create as many service appointments as the services in the work order.   

  

Appointments can be created from the  **Work Orders** module or the [**Service Appointments**](https://help.zoho.com/portal/en/kb/fsm/work-order-management/articles/service-appointments) module.

  

To create a service appointment:  

1. Select **Work Orders** from the **Work Order Management** menu and click the work order you want to add the service appointment to.  
    
2. Select the **Appointments** tab and click **Create Appointment**.  
    
3. Enter the following details, then click **Schedule**:  
    

4. A **Summary** for the service appointment
5. The values for **Scheduled Start Date Time** and ****Scheduled** End Date Time**.  
    The **Scheduled Start Date Time** and **Scheduled End Date Time** should be within the same day. Use [multi-day scheduling](https://help.zoho.com/portal/en/kb/fsm/work-order-management/articles/service-appointments#Multi-day_appointments) to schedule an appointment spanning multiple days.
6. A **Field Agent** or a **Crew**  
    
    One or more resources can be assigned to the appointment.  
    
      
    
    |   |
    |---|
    |The following agents or crews will be available for assignment:  <br>  <br>- Active [agents](https://help.zoho.com/portal/en/kb/fsm/set-up-workforce/articles/field-technician-management) or [crew](https://help.zoho.com/portal/en/kb/fsm/set-up-workforce/articles/crew-management)s assigned to the territory that is chosen in the work order  <br>  <br>- The agents and crews who are active in the territory between the **Scheduled Start Date Time** and **Scheduled End Date Time  <br>  <br>-** During the time when a field agent is part of a crew, they will not be available as an individual resource.  <br>  <br>Hover over the name of an unavailable agent to know the reason for their unavailability.|
    
      
    

![](https://help.zoho.com/galleryDocuments/edbsndd42f56e7de7f2dba0938f03fea3ae3850bd458664fa66b4cad8583b88ccc5b0bb8d73dd235f0e4878ec0473f39eef54?inline=true)  
  
If you schedule an appointment without assigning it to a service resource (see screenshot below), then the appointment created will be in **[New](https://help.zoho.com/portal/en/kb/fsm/schedule-and-dispatch/articles/manage-service-appointments#Scheduling_Service_Appointments)** status.

  

![](https://help.zoho.com/galleryDocuments/edbsn0c668b4109963baed2610dca241768a48de83c7df105946eb3dadfea8ffe06e0849f063bb89d8dd9aebf23848907f26a?inline=true)  

### Creating Service Appointment for a Service  

You can create a service appointment for a work order or for the individual services. Appointments for the services can be created as explained below:  

  

To create a service appointment for a work order line item:  

1. Select **Work Orders** from the **Work Order Management** menu and click the work order you want to add the service appointment to.
2. Select **Appointments** tab and click **Create Appointment**.
3. Enter the following details, then click **Schedule**:  
    

4. In the **Service** field, retain the services or [service tasks](https://help.zoho.com/portal/en/kb/fsm/work-order-management/articles/service-tasks#Use_Service_Tasks_in_a_Service_Appointment) you want to create the appointment for.
5. A **Summary** for the service appointment  
    
6. The values for **Scheduled Start Date Time** and ****Scheduled** End Date Time**.  
    The **Scheduled Start Date Time** and **Scheduled End Date Time** should be within the same day. Use [multi-day scheduling](https://help.zoho.com/portal/en/kb/fsm/work-order-management/articles/service-appointments#Multi-day_appointments) to schedule an appointment spanning multiple days.
7. A **Field Agent** or a **Crew**  
    One or more resources can be assigned to the appointment.  
    

A new service appointment can be created for the work order or service after the existing one has been canceled or terminated.  

  

![](https://help.zoho.com/galleryDocuments/edbsn2753fae6f2132802f8027af51d0a481957e430216785ec36fbf3b42dbff1968efa75e3db889cbbfb6fbbfdf587c3d027?inline=true)  

### **Link Service**

You can link a service which has been newly added to a work order, to one of its service appointments. Following are the steps to link a service to an existing service appointment:  

1. Add a service to an existing work order.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn3056f57829d9559b7a50710846e8a5b1cce0d87d26c2edc28906388c34c5f0165c5fedcf86fb09c9b0a8376905c32177?inline=true)  
      
    
2. Select the service appointment of the work order to which you want to add the newly added service. Click **Link Service**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn1fbbce983bf9fbf53f09a4d1a2afef400c7c866659e17554ce4226e0b1e148d895379cb76029f026758fa0ff7400ebfa?inline=true)  
      
    
3. Click **Save**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsnc0c787e4caa917a5a6cad018e38e00cb8e355723343553b546b98aa886415c8b73128fcb2a28b053467c607797f38c59?inline=true)

The service will get added to the service appointment.

  

![](https://help.zoho.com/galleryDocuments/edbsna5db7a9e42752d5274062d0855468d7a6b6a5c09fd777db725ab0fca22f4b578c1d04c0144103bfc98ebdf1709cac9b1?inline=true)