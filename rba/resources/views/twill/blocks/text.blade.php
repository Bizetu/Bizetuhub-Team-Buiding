@twillBlockTitle('Section')
@twillBlockTitleField('title', ['hidePrefix' => true])
@twillBlockIcon('text')
@twillBlockGroup('app')

<x-twill::input
    name="quote"
    type="textarea"
    label="Quote text"
    :maxlength="250"
    :rows="4"
/>