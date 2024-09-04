## How to Install

1. Upload the `.zip` file to your WHMCS installation folder.
2. Extract the `.zip` file within the installation folder.
3. Open the **WHMCS Admin Page**, navigate to **Setup** -> **Products/Services**.
4. Enter your password to access the setup page.
5. Find the product **'Sectigo Positive SSL Testing'**.
6. Click or select the **Edit** button.

### **Module Settings** tab

1. In the **Module Name** field, select **Aksaradata SSL** from the dropdown.
2. Select the appropriate server group from the **Server Group** dropdown or leave it as **None** if not required.
3. Set **Grant Type** to **client_credentials**.
4. Enter the **Client ID** and **Secret Key** provided by Aksaradata. You can get from https://developer.irsfa.id, for API Documentation please read here: https://developer.irsfa.id/documentation/
5. Select the appropriate SSL product from the **SSL Product** dropdown (e.g., **Commercial SSL - [Certum]** or **Commercial Wildcard SSL - [Certum]**).
6. In the **Aksaradata API URL** field, input `https://api6.irsfa.id` or custom URL that you set in IRSFA.
7. In the **SSL Panel URL** you can leave it blank.
8. Choose the default language (e.g., **en_GB**) from the **Default language** dropdown.
9. Choose the desired product setup option, or select **Do not automatically setup this product** if preferred.
10. Click the **Save Changes** button to apply the settings.

### **Custom Fields** tab

1. Add CSR Field
- Under **Field Name**, enter `CSR`.
- Set **Field Type** to `Text Box`.
- Set **Display Order** to `0`.
- Select **Admin Only**.
2. Add Approval Email Field
- Under **Field Name**, enter `Approval Email`.
- Set **Field Type** to `Text Box`.
- Set **Display Order** to `0`.
- Select **Admin Only**.
3. Add Domain Validation Method Field
- Under **Field Name**, enter `Domain Validation Method`.
- Set **Field Type** to `Text Box`.
- Set **Display Order** to `0`.
- Select **Admin Only**.
4. Add ssl_irsfa_id Field
- Under **Field Name**, enter `ssl_irsfa_id`.
- Set **Field Type** to `Text Box`.
- Set **Display Order** to `0`.
- Select **Admin Only**.
5. Add Registry ID Field
- Under **Field Name**, enter `Registry Id`.
- Set **Field Type** to `Text Box`.
- Set **Display Order** to `0`.
- Select **Admin Only**.
6. Add contact_id Field
- Under **Field Name**, enter `contact_id`.
- Set **Field Type** to `Text Box`.
- Set **Display Order** to `0`.
- Select **Admin Only**.
7. Add is_verified Field
- Under **Field Name**, enter `is_verified`.
- Set **Field Type** to `Text Box`.
- Set **Display Order** to `0`.
- Leave blank checkbox in the bottom.
8. Once all custom fields have been added, click **Save Changes** at the bottom of the page.


