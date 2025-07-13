
---
## Add Contact Details  

Contacts can be added in the following ways:  

1. Add contacts individually in the **Contacts** module. The details are described below.      
2. [Import contacts](https://help.zoho.com/portal/en/kb/fsm/data-administration/articles/importing-data-to-zoho-fsm) from external sources.      
3. Import contacts from [Invoice](https://help.zoho.com/portal/en/kb/fsm/billing/articles/module-configuration#Import_Customers).

To add a contact:

![Info](https://static.zohocdn.com/zoho-desk-editor/static/images/info.png/)**Permission Required**: [Contacts](https://help.zoho.com/portal/en/kb/fsm/set-up-workforce/articles/profiles-and-permissions#Basic_Permissions)

1. Select **Contacts** from the **Customers** menu and click **Create**.      
2. Enter the **Last Name**.      
3. Enter an **Email** address.      
4. Associate the contact with a **[Company](https://help.zoho.com/portal/en/kb/fsm/customer-management/articles/manage-companies)**.
5. Add [Address](https://help.zoho.com/portal/en/kb/fsm/customer-management/articles/add-fsm-contact#Add_Contact_Address).      
6. Select a value for **Taxable**:  
    **- Taxable**: A Company Tax should be selected if Taxable is chosen.  
    **- Non-Taxable**: An [Exemption Reason](https://help.zoho.com/portal/en/kb/fsm/billing/articles/tax-setting#Tax_Exemption) should be selected if Non-Taxable is chosen.
    
    These values are configured in Zoho Invoice. Click [here](https://help.zoho.com/portal/en/kb/fsm/integrations/invoice-integration/articles/invoice-setup) for details of Zoho Invoice-FSM integration. These values can also be edited in the FSM application at **Setup** > **Integrations** > **Billing** > **Tax Setting**.    
7. Select a **Currency**.      
    The Currency and Exchange Rate will be displayed only if [multiple currencies](https://help.zoho.com/portal/en/kb/fsm/getting-started/articles/organization-setting#Manage_multiple_currencies) are enabled.   
8. Add any other necessary contact details and click **Save**.  
    

![](https://help.zoho.com/galleryDocuments/edbsn4ad2eb7fecfebed5c66064873364c2560b841ecd22a984a9e97b8d72a53e1838af3a945107d9c9cc0753d1cca25b7c5f?inline=true)  

  

![Info](https://img.zohostatic.com/zde/static/images/info.png)

The contacts associated with a company should have unique email addresses.  

## Add Contact Address  

To add addresses to a contact, do the following in the _Create_/_Edit Contact_ form:

1. Click the **Service Address** or **Billing Address** field and click **Create New**.     
2. In the _Add Address_ overlay, enter the details.      
3. Click ![](https://help.zoho.com/galleryDocuments/edbsn90b7172d52a258fced6c5656542443f7683da48768f848b85fad3e78f8d33ccf35ff434b45df64ae554757c828ca0306?inline=true) to populate the address geocodes (latitude, longitude).  
    You can also edit the geocodes.
4. Enter other necessary details and click **Save**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsnaa3686877460097e6ce89da92dd24b24f01f318cc436664f6a38fee54d27c1926337d4896d3a79507ff0ee1562361749?inline=true)  
    

You can also add additional addresses to a contact from the _Contact Details_ page.  
  
To add addresses from the _Contact Details_ page:  

1. Select the **Addresses** tab and click **Create**.      
2. In the _Add Address_ overlay, enter the necessary details and click **Save**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsncf610099632c9d6b374d95a9ea45db4ed0a8f89adc3f6e0a7c778aec043827d3f2f8230c2cda6aa4ccc6da93f15e29c6?inline=true)  
    

Any of these addresses can be [assigned](https://help.zoho.com/portal/en/kb/fsm/customer-management/articles/add-fsm-contact#Using_Contact_Addresses) as a Service or Billing address of the contact.  

## Delete Contact Address

You can delete the addresses added to a contact. To delete a contact address:  

1. Select **Contacts** from the **Customers** menu and select the contact record whose address you want to delete.      
2. Select the **Addresses** tab.
3. Hover over the address and click the **Delete** [![](https://help.zoho.com/galleryDocuments/edbsn8a58160e3c658edf6dfeb68ced6b720d66cbcdfa64d09725db51c3cce45fef4e70e4963a88b956a8e50c68a244517a35?inline=true)] icon.      
4. Click **Yes, Delete** in the confirmation message to proceed.  
    

![](https://help.zoho.com/galleryDocuments/edbsn193d83f17eead6ff0a587d307537330ed9556e45e8c83ba610bd28fe4339fed7c682a539b84dc152b4d76b3512708ece?inline=true)  

![Info](https://img.zohostatic.com/zde/static/images/info.png)

**Note**:  

1. Deleting an address will not affect any existing records (work orders, service appointments, etc) where this address is used.      
2. You can delete a contact address only if you have the **Delete** permission for the Contact, and Company modules.  
    

### Delete Contact Address from Mobile App

To delete a contact address:  

1. Open the record and tap the **Addresses** tab.      
2. Tap **more options** [ ![](https://help.zoho.com/galleryDocuments/edbsnda534cfbe9897402e4517a5b732e4676aa92bfa185153e433957a1f27d3ae195ea4f0b5a42aa4fac641f0ee59855c81b?inline=true) ] in the address entry and select **Delete**.  
    ![](https://help.zoho.com/galleryDocuments/edbsn3199a9f8e4f36cb406409c11a9703d48bfe58f44c576d1f7dbbae94bc29d80bde3afb856d6546e55438b41b791927dbf?inline=true) 

## Using Contact Addresses  

You can perform the following actions on an address, Service or Billing, in the _Edit Contact_ form:

1. **Choose another address** : If there are multiple addresses present, then click the **Choose another** icon [ ![](https://help.zoho.com/galleryDocuments/edbsn33130466bd80631bfcb5146b2f9b867f698ec979b186845ab8f0a7d253bccb20b8234fcc6252b879c2eef26d2c681333?inline=true) ] to select another address. In the _Select Billing/Service Address_ pop-up, select the desired address.  
      
    
    ![](https://help.zoho.com/galleryDocuments/edbsn9c7c903507d50d56168fd55aec31d8219b05fc746625a3c2ca6b1ec3982663bec3892fc45be2dbdf3118b7cfa0453c7b?inline=true)  
    
      
    
2. **Edit address** : After adding an address in the Service/Billing address field, click the **Edit** icon [![](https://help.zoho.com/galleryDocuments/edbsnfad17b6dbb09c0379f762cb1343a6856ebfc4bb0403007c42385a0864593fd91d27487abedf43111b84d8b60104466df?inline=true)]. In the _Edit Address_ overlay make the necessary changes and click **Save**.  
    
      
    
    ![](https://help.zoho.com/galleryDocuments/edbsna4c31701bc8320a297c33232bfab9b97c2407a23abd29823b708189e5120ed49a37219d2f4f3438ff0ca22c84ee471c8?inline=true)  
    
      
    
3. **Add address** : Multiple addresses can be added to a contact. Click the **Add** icon [![](https://help.zoho.com/galleryDocuments/edbsnc1f484037eb31204fbbe5817df38b26c5830016dcfda925f8d9aab6993e6a53f74298b6502b6d92f9472ade7c6f41a0a?inline=true)]. In the _Add Address_ overlay, enter the details and click **Save**.

## Delete Contacts  

Contacts can be deleted either from the list view page or the contact details page. Contacts associated with active Requests, Estimates, Work Orders, or Appointments can not be deleted.  

  
To delete contacts from the list view:  

1. Select the **Contacts** module and select the checkboxes of the contacts you want to delete.  
2. Click **Delete**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn022f9be8cb908c276908262a2f7c3892b7b58515f5263ded7a400b4641f23ab580f12c800dfa622ea82ebb31ca95155a?inline=true)  
      
    
3. Click **Delete** in the confirmation message.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn8ab3ac15c23e70d1dbb7389db0ba797f203801863d15e28dcd8068846cc4518c97f2f5ab5a2c6d939f130a933aeea3d4?inline=true)  
      
    If there are contacts which are associated with active Requests, Estimates, Work Orders, or Appointments, then they cannot be deleted. The outcome of the deletion will be displayed (see the screenshot below).  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn2d03d79b3a9492d6a3933342189974a3f10d3b5d5349895cea6cc62bf4bf60c5d568356f7d1e558c00f3f3e57bb9f5d6?inline=true)

To delete a contact from its details page:  

1. Select the **Contacts** module and click the contact record you want to delete.      
2. Click **Delete**.      
3. Click **Delete** in the confirmation message.  
    If there are contacts which are associated with active Requests, Estimates, Work Orders, or Appointments, then they cannot be deleted. The outcome of the deletion will be displayed (see the screenshot below).  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn884a6a99dca3f7470636f1f7c711cc1cf947b0c11a84ec83743bebad8ce8e166f25d7e27a2edd69d67ddecbdfe1c00e5?inline=true)

![Notes](https://img.zohostatic.com/zde/static/images/file.png)

**Note**: Deleted contacts cannot be recovered.  

## Add Contact from Mobile App  

To create a contact:  

1. Select **Contacts**, in the left menu.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn86590d2bc8ba7ceb3ebcbdfdcb6d8ded2e816fed2ac4fb43aa32031974322559f03c13f8847bd72aba6ab6a0eeb3297b?inline=true)  
      
    
2. In the _Contacts_ screen, tap the add [+] icon.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsnb59b077cee36bcc1f09d60c15ab87c4e919d27833cec052fbd6cae48ad342a47992d82eb4be5d532750f84bf56f2fb93?inline=true)  
      
    
3. In the _Create Contact_ page, enter the necessary details and click **Save**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn37e8a51b2ced8910f3ac3a23fada45ba89b550b8e5b7f0d164f83d884663cc4b22750fb0680699b0136a808b62fd8b5a?inline=true)  
      
    
4. In the lookup fields, you can do an [advanced search](https://help.zoho.com/portal/en/kb/fsm/mobilize-your-workforce/articles/fsm-mobile-app#Advanced_Lookup_Search).      
5. In the address fields, you can [add](https://help.zoho.com/portal/en/kb/fsm/mobilize-your-workforce/articles/fsm-mobile-app#Manage_Work_Order_Addresses) addresses.      
6. The Currency and Exchange Rate will be displayed only if [multiple currencies](https://help.zoho.com/portal/en/kb/fsm/getting-started/articles/organization-setting#Manage_multiple_currencies) are enabled.
The created contact can be edited. To edit a contact:  

7. Click the **Edit** [![](https://help.zoho.com/galleryDocuments/edbsn1e0acdad80c3089d404cb616b028954b34752c01c8a730da8683adf700450b6360fe0314b9e47cb2d94d6d6211bb0b9d?inline=true)] icon on the top right side.  
    
8. Make the necessary changes and click **Save**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn4a001a9e62ec108c4f2d577940e8576cbb8d662ce4ece62880444062e1cb9ed9cf5e2495c58b4e92e73d490304097669?inline=true)     ![](https://help.zoho.com/galleryDocuments/edbsn0ed4f6004a3af49d5c93a88858cb0bbab9a6859f519f734182bd68d60e0242dda4ea44e4f2c628267e5ac082b2b1ca62?inline=true)

You can also add addresses, and notes.