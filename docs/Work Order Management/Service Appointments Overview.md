# Service Appointments

Service appointments are jobs created for carrying out the services in a work order. These service appointments are assigned to field agents or crew who will then perform the services at the contact location. As many service appointments as the services in the work order can be created. The service appointments can be viewed under the **Service Appointments** module. Service appointments can be created and managed here. Appointments can also be created from within a [work order](https://help.zoho.com/portal/en/kb/fsm/work-order-management/articles/work-orders#Create_Service_Appointments).  

  

---

## Create Service Appointments  

- **Permission Required**: [Service Appointments](https://help.zoho.com/portal/en/kb/fsm/set-up-workforce/articles/profiles-and-permissions#Basic_Permissions)  

- Find out the Edition-specific limits for [Service Appointments](https://help.zoho.com/portal/en/kb/fsm/getting-started/articles/fsm-pricing).  

  

To create a service appointment:  

1. Select **Service Appointments** from the **Work Order Management** menu and click **Create**.  
    
2. Select the work order that you want to create the service appointment for.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsnc49b00c6a8365365e6e1f4073d8a9069412777b6b7a697b1371fe496135d99610a6e0cd74aeb822383f05b43c6c175d9?inline=true)`  
    The details of the selected work order are displayed.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn08e51d7c73c4c0c2f3dea3bb441480256744603678cd2d343de9f2def15ac62a314b8c4f8859a24b0e15de104a976ab9?inline=true)  
      
    
3. Enter the following details, then click **Schedule**:  
    

4. The values for **Scheduled Start Date Time** and **Scheduled End Date Time**.  
    The **Scheduled Start Date Time** and **Scheduled End Date Time** should be within the same day. Use [multi-day scheduling](https://help.zoho.com/portal/en/kb/fsm/work-order-management/articles/service-appointments#Multi-day_appointments) to schedule an appointment spanning multiple days.
5. A **Service Resource** (Agent or Crew)  
    
    One or more resources can be assigned to the appointment. Only when the **Scheduled Start**/**End Date Time** is selected will the service resources be listed.  
    
      
    
    |   |
    |---|
    |The following agents or crews will be available for assignment:  <br>  <br>- Active [agents](https://help.zoho.com/portal/en/kb/fsm/set-up-workforce/articles/field-technician-management) or [crew](https://help.zoho.com/portal/en/kb/fsm/set-up-workforce/articles/crew-management)s assigned to the territory that is chosen in the work order  <br>  <br>- The agents and crews who are active in the territory between the **Scheduled Start Date Time** and **Scheduled End Date Time** - During the time when an agent is part of a crew, they will not be available as an individual resource.|
    
      
    Hover over the name of an unavailable agent to know the reason for their unavailability.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn469e5fecd77b881b1a7f755dbe30216ae67399323e30c392d95da46303d1614feaca7fc67f7c9e30106219946f95c78d?inline=true)  
      
    

6. Click **Confirm** in the confirmation message.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn9c65f425fb915817bc1bf383fd561c681c761e9c210bc7d4f9e655cd4b4e4c1f5e5ae156c3272f7ec11bad0f5ca3a872?inline=true)

The service appointment details page will be displayed. This page will display the name of the work order for which the service appointment has been created. You can also dispatch, reschedule, terminate, and Cancel an appointment from this page.  

  

![](https://help.zoho.com/galleryDocuments/edbsn5d26094fdff29e9c91f4b9879c688108cc08775de29c693de95112424bcc57ba04bf3df99b08fd88aab0b6b1b8400a04?inline=true)  

  

The appointment created can also be viewed under the **Appointments** tab of the work order for which the service appointment has been created. Under the **Service and parts** tab of the work order, you can see the appointment created for each service line item. Hover over the appointment icon [ ![](https://help.zoho.com/galleryDocuments/edbsn5fdd5024d163827971c91ea41a338726ce3d7dbee7a91baec114700f1cd633561f28ea67485ca5625682fb341f176bf4?inline=true) ] next to the **Service Line Item Name** to see the appointment details. Click to navigate to the _Appointment Details_ screen.

  

![](https://help.zoho.com/galleryDocuments/edbsn3ea4c93d93349b1b362e4c4d5ddd4034885e20f0cf76feb9e20e02118ed6b847772c9390c275f9bbd198c89f201d3c22?inline=true)  

### Creating Service Appointment for a Service  

You can create a service appointment for a work order or for the individual services. Appointments for the services can be created as explained below:  

  

To create a service appointment for a service line item:  

1. Follow steps 1-2 mentioned [above](https://help.zoho.com/portal/en/kb/fsm/work-order-management/articles/service-appointments#Create_Service_Appointments).  
    
2. Enter the following details, then click **Schedule**:  
    

3. In the **Service** field, retain the services or [service tasks](https://help.zoho.com/portal/en/kb/fsm/work-order-management/articles/service-tasks#Use_Service_Tasks_in_a_Service_Appointment) you want to create the appointment for.
4. The values for **Scheduled Start Date Time** and **Scheduled End Date Time**.  
    The **Scheduled Start Date Time** and **Scheduled End Date Time** should be within the same day. Use [multi-day scheduling](https://help.zoho.com/portal/en/kb/fsm/work-order-management/articles/service-appointments#Multi-day_appointments) to schedule an appointment spanning multiple days.
5. A **Service Resource** (Field Agent or Crew)  
    One or more resources can be assigned to the appointment.

![](https://help.zoho.com/galleryDocuments/edbsnaf32add98fedb6b7aa40853c6f3a0b44435abc0f2db836ade769640e8bbc9d7aface7981ac99b6c1ce0541bf65bcdcfe?inline=true)  

  

Once service appointments have been created for all the service line items of a work order, then new service appointments can be created only after the existing ones have been cancelled or terminated. In this scenario, a message will be displayed as shown in the screenshot below.  

  

![](https://help.zoho.com/galleryDocuments/edbsn64a363ab01698f743d9459a303887ccfff21d91cc131cb012193d66236f7272f72375f9022ccdaf3a4fab7c4103ec8f3?inline=true)  

### Multi-Appointment Scheduling   

At a time, you can schedule multiple service appointments from the work orders. This option is available in the Maps view of the Dispatch Console.  

   

To schedule multiple appointments:  

1. Navigate to the **Dispatch Console** module and click the **Maps** tab.  
    
2. Click the **Work Orders** filter.  
    
3. Filter the work orders by the **New** Status.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn5d43609d2afa6b53af5ae47229b3a3ec2781fab2020f04a55e8c4a670a56be297a9a6ac45810d25ba777975a0b8bcfc6?inline=true)  
      
    To filter the work orders, click **Edit** next to **Filtered By**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsnf2aca7fc9b7eb514b9ccf8b30abb7acdaf581265339b9704f3502d664d936b7a13bc9a38cad9452292eb8e156fc24e3c?inline=true)  
      
    
4. Hold the **Command** (for Mac) / **Ctrl** (for Windows) key and click on the markers for the work order for which you want to create the service appointments and click **Schedule Appointment**.  
    The selected markers will be indicated as ![](https://help.zoho.com/galleryDocuments/edbsn8fd5101ea79445640fd1f5607e8f75195198da718263cc75709fae63a4258e576c391641398671f9708c727409ce8117?inline=true) or ![](https://help.zoho.com/galleryDocuments/edbsn862b86e0dddcb70e5fa6e55e655f095a8cf91b3d20f65b5eac3d3a4a623c3af1e0ca346e0a4c5a730126db23ae2db4ea?inline=true). The selected work orders will be listed in the left panel. You can select a maximum of 10 work orders.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsnad90001efd08addafd94140baf3793602e88fddfdcea5d43f7d580d171ded4b0a72b00b29d29f7be7e347c29e3359dfd?inline=true)  
      
    
5. Select the desired scheduled dates, assign the service resources, and click **Schedule** or **Schedule and Dispatch**.  
    By default, the schedule dates will be prepopulated based on the [Business Hours](https://help.zoho.com/portal/en/kb/fsm/getting-started/articles/organization-setting#Mark_Business_Hours). If business hours are not configured, then schedule dates starting from 12 am will be prepopulated. The time gap between the End Time of an appointment and the Start Time of the next will be based on the **Interval** selected.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn7bd78e42aecc43b0a089086cf0b1e60d0147aeac0648c6718b8284def9e64ab99c9179252eb9ab9304d40d7b0a46629b?inline=true)  
      
    You should schedule the appointments such that their timings don't overlap.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn847a716757733b79fd3635dad09e9069faa9ff2e24fdd2728cd1bc5acdd06dbaa0d008e78faa8a6c57cda0050ca808fb?inline=true)  
    

The created service appointments will be listed.  
  
![](https://help.zoho.com/galleryDocuments/edbsn0f769d4feb14bdd25ce78f2bd5b2f402330201d68ae1199064894e909742b4d0d00284b035cfe24bbe10e52d23ac2629?inline=true)  

You will not be allowed to choose work orders assigned to different territories.  
  
![](https://help.zoho.com/galleryDocuments/edbsn08bb07666c9128fcee6674987acbd4ccf42a1fa701ac13038f2d923f324c1bd53ba451b7a0796fc3250d45a3c2fa89fc?inline=true)  

![Info](https://static.zohocdn.com/zoho-desk-editor/static/images/info.png)

**Points to remember**  

1. You can only choose work orders in the New status to create the appointment.  
    
2. For the work orders chosen, all the service line items in the New status will be considered while creating the appointment.  
    

## Multi-day appointments  

You can create appointments that span over multiple days. This will help you schedule appointments for complex or time-consuming services. E.g. Installing large or complex equipment may require technicians to spend multiple days on-site to ensure proper installation and set-up. This could include tasks like wiring, configuration, testing, and training. Multi-day appointments can only be created in the [Professional](https://help.zoho.com/portal/en/kb/fsm/getting-started/articles/fsm-pricing#Complete_Feature_Comparison) edition of Zoho FSM. Please refer to [this](https://help.zoho.com/portal/en/kb/fsm/work-order-management/articles/service-appointments#Appointment_Usage_Calculation) section to know how the appointment usage will be calculated for multi-day appointments.  

  

![](https://help.zoho.com/galleryDocuments/edbsn939e9a3656d5d4d3d5ebffdc2c7a5d292395c61fc1c441d1da5940e25710f5cb111bccb70c426ecdd8a1f830608af4b1?inline=true)​  

## Edit Service appointments  

To edit service appointments:  

1. Select **Service Appointments** from the **Work Order Management** menu.  
    
2. Click the service appointment you want to edit and in the _Service Appointment details_ page, click **Edit**.  
    
3. Click **Save** after making the changes. 

## Delete Service appointments   

To delete a service appointment, you need to have the following permissions:  

1. **Delete** permission of [Service Appointments](https://help.zoho.com/portal/en/kb/fsm/set-up-workforce/articles/profiles-and-permissions#Module_Permissions)  
    
2. **Delete** permission of the following related records:  
    

3. Time sheets  
    
4. Service report  
    
5. Trips  
    
6. Notes and Attachments

The deletion of the related records will also depend on whether you have the permission to delete either **All Records** or **Own Records** of the related records.

   

To delete a service appointment:  

1. Select **Service Appointments** from the **Work Order Management** menu and click the service appointment you want to delete.  
    
2. In the _Service Appointment Details_ page, click **Delete** from the **Edit** button.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn1daff1f35fb6468168ac5dcd614ef3e977bc80e3d410e3be7abe821d6abc2fdfd85ed63d302027640f5b715e9b0a3ee7?inline=true)  
      
    Alternatively, in the __Service Appointment_ List_ page hover over a service appointment, click the **More Options** [ ![](https://help.zoho.com/galleryDocuments/edbsn44ef696dbd172b41881d93341701f640ff0522e345948a6334617005e45fbac3b5f3c00425fcd8eeccb263063843faf1?inline=true) ] icon and select **Delete**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsnf7543f99dc49b63e15be7941f5f32f703209e2d0d01f4d144716ba529204e757fc3706bf6e2a15251e162e4e3cb121e3?inline=true)  
      
    
3. Click **Delete** in the _Delete Service Appointment_ popup.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn7bcfc8a931e9187ffcda64bb3c4b693b516a27ba857ffd2598d0e97b60c6a89e754cfdc423cbd20c4c99ca5b355c4937?inline=true) 

If the user doesn't have permission to delete any of the related records, then they will not be able to delete the service appointment.  

   

![](https://help.zoho.com/galleryDocuments/edbsnb654df53e56d56c3cbcd78b9f81b63c5964bfc37b999b7fb39d60d0421ff73c3959b75cd397273f7999f666ac6426672?inline=true)  

   

**Note**: Service appointments of any status can be deleted.  

![Info](https://img.zohostatic.com/zde/static/images/info.png)

**Points to remember**  

1. The deleted appointments will be deducted from the number of [appointments](https://help.zoho.com/portal/en/kb/fsm/getting-started/articles/fsm-pricing#Volume_based_pricing) available for a month.  
    
2. Instead of deleting the service appointment and losing data, you can consider [Cancelling](https://help.zoho.com/portal/en/kb/fsm/work-order-management/articles/service-appointments#Cancel_Service_appointments) the service appointment.  
    
3. In case you want to change the appointment timing, you can consider [Rescheduling](https://help.zoho.com/portal/en/kb/fsm/schedule-and-dispatch/articles/manage-service-appointments#Rescheduling_Service_Appointments) the appointment.  
    
4. When a service appointment is deleted, an entry will be included in the timeline of the parent work order indicating this.

## Complete Service Appointments  

To complete a service appointment:  

1. Select **Service Appointments** from the **Work Order Management** menu.  
    
2. Click the service appointment you want to complete and in the _Service Appointment details_ page, click **Complete Work**.  
    
3. In the confirmation message, click **Proceed**.

![](https://help.zoho.com/galleryDocuments/edbsne072207fe1a9c272da0c18c2ab504b888aa2704f2121b1671cec291fb17089a2fca3c3401637f8be56580b8cf1796146?inline=true)  

   

The above confirmation message will appear if the status of the service line items is either In Progress, or Completed.  

   

If the status of one of the service line items is Partially Completed, then when you complete the service appointment, you will have two options:  

1. Complete all the services  
    
2. Do not complete partially completed services

![](https://help.zoho.com/galleryDocuments/edbsnfd58ac6c599973f96faced8393ec3e53a77a55421cc5f0d0b0129bdd0fb005ed8c0ab659da426ded6dff4ec77bb29d4e?inline=true)  

   

**Complete all the services**: If you choose this option, the status of the service line items will change to Completed and the status of the service appointment will change to Completed.  

   

**Do not complete partially completed services**: The status of the service appointment will change to Completed. The status of the Partially Completed service line items will remain as is and the status of the other service line items will change to Completed. You can Resume Work on the Partially Completed service line items at any time.  

   

![](https://help.zoho.com/galleryDocuments/edbsnb9a4ea2f69381e07ad0b44b042384b706ffab6a21188dd778c9764aea40bbcd3a08f87e5e2322c001cdeb99037c52aaf?inline=true)  

## Cancel Service appointments  

There may be instances when you do not want to proceed with a service appointment. For example, customer who had requested for an AC installation decides to not go ahead with the installation for the time being. In this case, the service appointment created for the this request will have to be cancelled.  

  

When a service appointment is cancelled, its status changes to **Cancelled**.  

  

To cancel a service appointment:  

1. Select **Service Appointments** from the **Work Order Management** menu.  
    
2. Click the service appointment you want to cancel and in the __Service Appointment_ details_ page, click **Cancel**.  
    The Cancel option will be available till the service appointment is completed.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn1bccffd3c341921729bed09ada00fe01cbfb1acbc33232b4dc195d2f1ad538b01b8c1ec8fb846363b4336cdc6eb5540d?inline=true)  
      
    
3. Click **Save** in the confirmation message.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn7872a5f207cacc6f8c2764853398fe00e849e1db6acdde1807378b6c782631f54ee0dde75d634cc84a6761420dbc7604?inline=true)  
    

## Terminate Service appointments

There may be instances when you cannot proceed with a service appointment. For example, the field agent goes to the service location and finds out that the customer is not present at the service location, or the field agent is unable to carry out their task due to faulty equipment. In these cases, the service appointment created for the this request will have to be terminated.

  

When a service appointment is terminated, its status changes to **Cannot Complete**.  

  

To terminate a service appointment:

1. Select **Service Appointments** from the **Work Order Management** menu.  
    
2. Click the service appointment you want to terminate. In the ___Service Appointment__ details_ page, click **Terminate**.  
    The Terminate option will be available till the service appointment is completed.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsnaa567695e3f186b8e6f029da9ec0d2afe1cbdb19d913629f3fd696106a161f06e496518845b9637cc69e218905cc6881?inline=true)  
      
    
3. Click **Save** in the confirmation message.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn0773519007f1bf49d4929c80758665f5e65b78b080496694337397696c99844ce07bb23365a346b40dfc54fdbcec22c6?inline=true)  
    

## Appointment Usage Calculation  

The appointment usage of a service appointment refers to the number of appointments that will be deducted from your total available appointments for it. This usage is calculated based on the time sheets of the service appointment. Specifically, the number of appointments deducted will correspond to the number of distinct dates in the time sheets created. Refer to the example below to understand this better:  

   

Example  

   

|   |   |   |   |   |
|---|---|---|---|---|
|**Time sheet**|**Start Date/Time**|**End Date/Time**|**Number of Distinct Dates**|**Number of Appointments Deducted**|
|TS2|Jul 01, 2024 01:06 PM|Jul 01, 2024 02:45 PM|1|1|
|TS3|Jul 24, 2024 09:00 AM|Jul 25, 2024 03:03 PM|2|2|
|**Total Appointments Deducted**|   |   |   |**3**|

   
The Zoho FSM [Org timezone](https://help.zoho.com/portal/en/kb/fsm/getting-started/articles/organization-setting#Company_Details) will be considered for the calculations. The details of the appointment usage can be seen in the left panel of a service appointment.

   

![](https://help.zoho.com/galleryDocuments/edbsn418d885863158d2ee9dccfa8760bffbd803dc7af1de119ddc178be7008955fee63863c058f271cb6125c8e8f20550d57?inline=true)  

   

When you have used up all your appointments, then you will encounter errors while creating service appointments or time sheets.  

   

![](https://help.zoho.com/galleryDocuments/edbsna96b8d1447ae7d4bf7b443cc6b4468dbb2920d7c9733e184a9e79481c0cb39371eb0fa9ffc7281687e13f2f755c6cf01?inline=true)  

   

**Points to remember**

1. When you create a service appointment, one appointment is automatically deducted. Later, when the total number of appointments to be deducted is calculated using the time sheets, this total includes the initial appointment deducted earlier.  
    
2. After appointments have been deducted, modifying the time sheets of the service appointment to reduce the number of distinct dates will not decrease the number of appointments already deducted.