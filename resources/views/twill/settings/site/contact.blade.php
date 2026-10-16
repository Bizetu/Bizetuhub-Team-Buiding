@twillBlockTitle('Contact and social details')
@twillBlockIcon('text')
@twillBlockGroup('app')

<x-twill::input name="site_name" label="Company name"/>
<x-twill::input name="contact_email" label="Public contact email" type="email"/>
<x-twill::input name="contact_phone" label="Public contact phone"/>
<x-twill::input name="office_location" label="Office location"/>
<x-twill::input name="working_hours" label="Public working hours" placeholder="Mon–Fri, 9:00 AM–5:00 PM (EAT)"/>
<x-twill::input name="instagram_url" label="Instagram URL" type="url"/>
<x-twill::input name="linkedin_url" label="LinkedIn URL" type="url"/>
