
app = app or {}

app.User = Backbone.Model.extend
    defaults:
        first_name: ''
        last_name: ''
        email_address: ''
        password: ''
        drivers_license_id: ''
        gender: ''
    