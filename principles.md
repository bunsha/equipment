# Gazingle app main principles

## Relations
### Polymorphism
* All services in app should register a models mutations as separate entity.
* All Mutations has same structure
````javascript
var mutation = {
    name: String, // field key
    display_name: String, // Mutation display name
    type: String, // type of a mutated field. Example: select, input, text, date, etc.
    values: String, // Predefined values for a mutation
    nullable: Boolean, // Allows null on a field
    overrides: Boolean, // Determines if mutation is overriding an existing field, or creates a new one
    hidden: Boolean, // Visibility in responses
    searchable: Boolean // Determines if model can be found by this field
}
// Example Mutation object
var equipment_meta = {
    name: 'last_service_at',
    display_name: 'Last service at',
    type: 'date',
    values: '',
    nullable: true,
    overrides: false, 
    hidden: false,
    searchable: true
}
````
* All Mutations can inherit each other.
<br />
<i>Problem: </i>
````javascript
// Mutation for Contacts Model
var contacts_meta = {
    name: 'first_name',
    display_name: 'First Name',
    type: 'text',
    values: '',
    nullable: true,
    overrides: false, 
    hidden: false,
    searchable: true
}

// Mutation for Contacts Model, which is modified for Vendor Model 
var vendors_contacts_meta = {
    name: 'first_name',
    display_name: 'Given Name',
    type: 'text',
    values: '',
    nullable: false,
    overrides: true, 
    hidden: false,
    searchable: false
}
````
* All entities in app should contain "meta" field, which is a storage of a model mutator data.
#####Meta object structure
````javascript
var meta = {
  key: String, // "name" field from Model Mutation 
  value: String // value of "name" field, for this specific instance of a Model
}
````