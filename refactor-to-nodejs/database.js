const mysql = require('mysql')

const config = {
    host : '127.0.0.1',
    user : 'root',
    password : '',
    database : 'lys_project'

}
const database = mysql.createConnection(config)

database.connect((error => {

    if(error){
        throw error
    }
    console.log('Connexion etablie avec la base de donnees')
}))



module.exports = {
    connection : mysql.createConnection(config)
}
