
admin = admin or {}

admin.User = Backbone.Model.extend
    defaults:
        first_name: ''
        last_name: ''
        email_address: ''
        password: ''
        drivers_license_id: ''
        gender: ''
    