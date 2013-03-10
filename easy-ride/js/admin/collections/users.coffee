admin = admin or {}

UserList = Backbone.Collection.extend
    model: admin.User

    localStorage: new Backbone.LocalStorage('users-backbone')

    nextOrder: ->
        return 1 if not @length
        return @last().get('order') + 1

    comparator: (user) -> user.get('first_name')

admin.Users = new UserList()