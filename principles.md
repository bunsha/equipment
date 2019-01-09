# Gazingle app main principles

## Relations
### Services Relations
* All services, except "Lead" can only communicate with Gateway and Messages/Events Hub
* All services relations, expect "Lead" Service, should store relations data on its own.
<br>
As an example, to manage a connection from leads to equipment, 
relational table should be placed under Equipment
<b>Equipment and Leads relation example:</b>

````
Equipment
----------------------
lead_id | equipment_id
----------------------
1       |  336
2       |  999
````

### Models
#### Polymorphism
* All services in app should register a models mutations as separate entity.
* All Mutations has same structure
````javascript
var mutation = {
    name: String, // field key
    display_name: String, // Mutation display name
    data_type: String, // type of a mutated field. Example: select, input, text, date, etc.
    values: JSON, // Predefined values for a mutation
    is_nullable: Boolean, // Allows null on a field
    is_replace: Boolean, // Determines if mutation is overriding an existing field, or creates a new one
    is_hidden: Boolean, // Visibility in responses
    is_searchable: Boolean // Determines if model can be found by this field
}
// Example Mutation object
var equipment_meta = {
    name: 'last_service_at',
    display_name: 'Last service at',
    data_type: 'date',
    values: '',
    is_nullable: true,
    is_replace: false, 
    is_hidden: false,
    is_searchable: true
}
````
* All Mutations can inherit each other.
<br />

````javascript
// Mutation for Contacts Model
var contacts_meta = {
    name: 'first_name',
    display_name: 'First Name',
    data_type: 'text',
    values: '',
    is_nullable: true,
    is_replace: false, 
    is_hidden: false,
    is_searchable: true
}

// Mutation for Contacts Model, which is modified for Vendor Model 
var vendors_contacts_meta = {
    name: 'first_name',
    display_name: 'Given Name',
    data_type: 'text',
    values: '',
    is_nullable: false,
    is_replace: false, 
    is_hidden: false,
    is_searchable: false
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