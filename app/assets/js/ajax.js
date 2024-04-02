function makeServiceAjax() {

    let baseUrl = "http://localhost:8082/";

    async function getUrl(url, postArgs, format) {
        return new Promise((resolve, reject) => {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onload = function () {
                if (xhr.status === 200)
                    resolve(xhr.response);
                else reject(xhr.status + " " + xhr.statusText);
            };
            xhr.onerror = function () {
                reject(xhr.status + " " + xhr.statusText);
            };

            xhr.send(format ? formatPost(postArgs) : postArgs);
        });
    }

    function formatPost(args) {

        let res = [];
        for (let key in args) {
            if (args.hasOwnProperty(key) && args[key] !== null) {
                res.push(key + '=' + encodeURIComponent(args[key]));
            }
        }
        return res.join('&');
    }

    function verifyLogin() {
        let id = localStorage.getItem('id');
        let token_id = localStorage.getItem('token_id');

        if (id === undefined || token_id === undefined) {
            return {};
        }

        return {
            "company_user_id": id,
            "token_id": token_id
        };
    }

    function addObj(obj1, obj2) {
        for (let prop in obj2) {
            if (!obj1.hasOwnProperty(prop) && obj2.hasOwnProperty(prop)) {
                obj1[prop] = obj2[prop];
            }
        }

        return obj1;
    }

    async function login(id, password, by_email) {
        return await getUrl(baseUrl + 'login/',
            (by_email ? "email=" : "company_user_id=") + id + "&password=" + password,
            false)

            .then((result) => {
                let obj = JSON.parse(typeof (result) === 'string' ? result : '');
                if ((obj.hasOwnProperty('company_user_id'))) localStorage.setItem('id', obj['company_user_id']);
                if ((obj.hasOwnProperty('token_id'))) localStorage.setItem('token_id', obj['token_id']);

                return {
                    "worked":true,
                    "content":obj
                };
            }).catch((error) => {
                return {
                    "worked":false,
                    "content":error
                };
            });
    }

    async function createCompany(name, surname, email, password, companyName) {
        let postargs = addObj(verifyLogin(), {
            "name": name,
            "surname": surname,
            "password": password,
            "email": email,
            "company_name": companyName
        });

        return await getUrl(baseUrl + "create/company/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function createUser(name, surname, email, password, acc_lvl = null) {
        let postargs = addObj(verifyLogin(), {
            "name": name,
            "surname": surname,
            "password": password,
            "email": email
        });

        if (acc_lvl !== null) postargs['accreditation'] = acc_lvl;

        return await getUrl(baseUrl + "create/user/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function createStorage(parent_storage_id, storage_name) {
        let postargs = addObj(verifyLogin(), {
            "parent_storage_id": parent_storage_id,
            "storage_name": storage_name
        });

        return await getUrl(baseUrl + "create/storage/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function createResource(storage_id, resource_name, resource_type) {
        let postargs = addObj(verifyLogin(), {
            "resource_name": resource_name,
            "resource_type": resource_type,
            "storage_id": storage_id
        });


        return await getUrl(baseUrl + "create/resource/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function createGroup(group_name) {
        let postargs = addObj(verifyLogin(), {
            "group_name": group_name
        });

        return await getUrl(baseUrl + "create/group/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function createResourceMinimum(resource_id, minimum) {
        let postargs = addObj(verifyLogin(), {
            "minimum": minimum,
            "resource_id": resource_id
        });


        return await getUrl(baseUrl + "create/resourceMinimum/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function createStorageMinimum(storage_id, minimum, resource_name=null,resource_type=null ) {
        let postargs = addObj(verifyLogin(), {
            "minimum": minimum,
            "storage_id": storage_id,
            "resource_name":resource_name,
            "resource_type":resource_type
        });


        return await getUrl(baseUrl + "create/storageMinimum/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function deleteUser(user_id) {
        let postargs = addObj(verifyLogin(), {
            "user_id": user_id
        });

        return await getUrl(baseUrl + "delete/user/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":true
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function deleteResource(resource_id) {
        let postargs = addObj(verifyLogin(), {
            "resource_id": resource_id
        });

        return await getUrl(baseUrl + "delete/resource/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":true
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function deleteGroup(group_id) {
        let postargs = addObj(verifyLogin(), {
            "group_id": group_id
        });

        return await getUrl(baseUrl + "delete/group/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":true
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function deleteStorage(storage_id) {
        let postargs = addObj(verifyLogin(), {
            "storage_id": storage_id
        });

        return await getUrl(baseUrl + "delete/storage/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":true
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function deleteResourceMinimum(resource_id) {
        let postargs = addObj(verifyLogin(), {
            "resource_id": resource_id
        });

        return await getUrl(baseUrl + "delete/resourceMinimum/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":true
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function deleteStorageMinimum(storage_id, resource_name=-1, resource_type=-1) {
        let postargs = addObj(verifyLogin(), {
            "storage_id": storage_id,
            "resource_name":resource_name,
            "resource_type":resource_type
        });

        return await getUrl(baseUrl + "delete/storageMinimum/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":true
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function toggleStorageGroup(storage_id, group_id) {
        let postargs = addObj(verifyLogin(), {
            "storage_id": storage_id,
            "group_id": group_id
        });

        return await getUrl(baseUrl + "toggle/storageGroup/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function toggleUserGroup(user_id, group_id) {
        let postargs = addObj(verifyLogin(), {
            "user_id": user_id,
            "group_id": group_id
        });

        return await getUrl(baseUrl + "toggle/userGroup/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function renameStorage(storage_id, storage_name) {
        let postargs = addObj(verifyLogin(),
            {
                "storage_id": storage_id,
                "storage_name": storage_name
            });

        return await getUrl(baseUrl + "rename/storage/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function renameResource(resource_id, resource_name) {
        let postargs = addObj(verifyLogin(), {
            "resource_id": resource_id,
            "resource_name": resource_name
        });

        return await getUrl(baseUrl + "rename/resource/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function renameCompany(company_name) {
        let postargs = addObj(verifyLogin(), {
            "company_name": company_name
        });

        return await getUrl(baseUrl + "rename/company/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function renameGroup(group_id, group_name) {
        let postargs = addObj(verifyLogin(), {
            "group_name": group_name,
            "group_id": group_id
        });

        return await getUrl(baseUrl + "rename/group/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function editQty(resource_id, qty) {
        let postargs = addObj(verifyLogin(), {
            "resource_id": resource_id,
            "qty": qty
        });

        return await getUrl(baseUrl + "editQty/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }


    async function editProfile(user_id, args){
        //args must be an object containing one or more of the following keys :
        //permission, name, surname, password, email

        let postargs = addObj(verifyLogin(), args);
        postargs = addObj(postargs, {"user_id":user_id});
        return await getUrl(baseUrl + "editProfile/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function getMissingResources(storage_id){
        let postargs = addObj(verifyLogin(), {
            "storage_id":storage_id
        });

        return await getUrl(baseUrl + "get/missingResources/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function getStorageContent(storage_id){
        let postargs = addObj(verifyLogin(), {
            "storage_id":storage_id
        });

        return await getUrl(baseUrl + "get/storageContent/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function moveResource(resource_id, storage_id){
        let postargs = addObj(verifyLogin(), {
            "storage_id":storage_id,
            "resource_id":resource_id
        });

        return await getUrl(baseUrl + "move/resource/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":true
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function moveStorage(storage_id, dest_storage_id){
        let postargs = addObj(verifyLogin(), {
            "storage_id":storage_id,
            "dest_storage_id":dest_storage_id
        });

        return await getUrl(baseUrl + "move/storage/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":true
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function getGroupsOf(user_id){
        let postargs = addObj(verifyLogin(),{
            "user_id":user_id
        });

        return await getUrl(baseUrl + "get/groupsOf/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function getCompanyInfo(){
        let postargs = verifyLogin();

        return await getUrl(baseUrl + "get/companyInfo/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function getLogs(offset=0, limit=10){
        let postargs = addObj(verifyLogin(), {
            "offset":offset,
            "limit":limit
        });

        return await getUrl(baseUrl + "get/logs/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function getAvailableStorages(){
        let postargs = verifyLogin();

        return await getUrl(baseUrl + "get/availableStorages/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function getUsers(limit=10, offset=0){
        let postargs = addObj(verifyLogin(),{
            "limit":limit,
            'offset':offset
        });

        return await getUrl(baseUrl + "get/users/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function getUserById(user_id){
        let postargs = addObj(verifyLogin(),{
            "user_id":user_id
        });

        return await getUrl(baseUrl + "get/userById/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function getResourceById(resource_id){
        let postargs = addObj(verifyLogin(),{
            "resource_id":resource_id
        });

        return await getUrl(baseUrl + "get/resourceById/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function getGroupById(group_id){
        let postargs = addObj(verifyLogin(),{
            "group_id":group_id
        });

        return await getUrl(baseUrl + "get/groupById/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function getGroupRelated(group_id){
        let postargs = addObj(verifyLogin(),{
            "group_id":group_id
        });

        return await getUrl(baseUrl + "get/groupRelated/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function getResourceMinimums(resource_id){
        let postargs = addObj(verifyLogin(),{
            "resource_id":resource_id
        });

        return await getUrl(baseUrl + "get/resourceMinimums/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    async function getStorageMinimums(storage_id){
        let postargs = addObj(verifyLogin(),{
            "storage_id":storage_id
        });

        return await getUrl(baseUrl + "get/storageMinimums/", postargs, true).then((result) => {
            return {
                "worked":true,
                "content":JSON.parse(typeof (result) === 'string' ? result : '')
            };
        }).catch((error) => {
            return {
                "worked":false,
                "content":error
            };
        });
    }

    return {
        login,

        createCompany,
        createUser,
        createStorage,
        createResource,
        createResourceMinimum,
        createStorageMinimum,
        createGroup,

        deleteUser,
        deleteStorage,
        deleteResource,
        deleteResourceMinimum,
        deleteStorageMinimum,
        deleteGroup,

        toggleStorageGroup,
        toggleUserGroup,

        moveResource,
        moveStorage,

        renameCompany,
        renameGroup,
        renameResource,
        renameStorage,

        editQty,
        editProfile,
        getMissingResources,
        getStorageContent,
        getGroupsOf,
        getCompanyInfo,
        getLogs,
        getAvailableStorages,
        getUsers,
        getUserById,
        getResourceById,
        getGroupById,
        getGroupRelated,
        getResourceMinimums,
        getStorageMinimums
    };
}
