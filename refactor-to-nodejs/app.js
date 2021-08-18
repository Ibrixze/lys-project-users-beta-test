const express = require('express')

const database = require('./database')
let users

const app = express()

app.use('/users', (request, response) => {

    database.connection.query('select * from users', (error, result) => {
        if(error)
            throw error
    response    
        .status(201)
        .json(result)
    })

})

module.exports = app 