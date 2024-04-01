//Constants used in all tags of the application

const CONSTANTS = {
    //Page to show
    //Disconnected
    HOME_PAGE: "HOME PAGE",
    REGISTER_PAGE: "REGISTER PAGE",
    LOGIN_PAGE: "LOGIN PAGE",
    //Connected
    STORAGE_PAGE: "STORAGE PAGE",
    COMPANY_PAGE: "COMPANY_PAGE",
    MEMBERS_PAGE: "MEMBERS_PAGE",
    GROUPS_PAGE: "GROUPS_PAGE",
    LOGS_PAGE: "LOGS_PAGE",
    NEW_USER_PAGE: "NEW USER PAGE",
    NEW_GROUP_PAGE: "NEW GROUP PAGE",

    //Regex
    LOWER_REGEX: '[a-z]',
    UPPER_REGEX: '[A-Z]',
    NUMBER_REGEX: '[0-9]',
    SYMBOL_REGEX: '[\\]\\[()?<>$^.*"\'&\-_/]',
    EMAIL_REGEX: '\\S+@\\S+\\.\\S+',

    //Rights levels
    SUPER_ADMIN: 0,
    ADMIN: 1,
    MODERATOR: 2,
    COLLABORATOR: 3,
    VISITOR: 4,

    //Resource types
    FOOD: "FOOD",
    TOOL: "TOOL",
    RAW_MATERIAL: "RAW MATERIAL",
    HARDWARE: "HARDWARE",
    SOFTWARE: "SOFTWARE",
    OFFICE_SUPPLY: "OFFICE SUPPLY",

    //Option menu types
    STORAGE: "STORAGE",
    RESOURCE: "RESOURCE",
    STOCK: "STOCK",

    //Log action target
    COMPANY_TARGET: "COMPANY TARGET",
    USER_TARGET: "USER TARGET",
    RESOURCE_TARGET: "RESOURCE TARGET",
    STORAGE_TARGET: "STORAGE TARGET",
    GROUP_TARGET: "GROUP TARGET",
    USER_GROUP_TARGET: "USER GROUP TARGET",
    STORAGE_GROUP_TARGET: "STORAGE GROUP TARGET",

    //Resource type name
    resourceTypeToString: function(type) {
        switch (type) {
            case CONSTANTS.FOOD:
                return "Food";
            case CONSTANTS.TOOL:
                return "Tool";
            case CONSTANTS.RAW_MATERIAL:
                return "Raw material";
            case CONSTANTS.HARDWARE:
                return "Hardware";
            case CONSTANTS.SOFTWARE:
                return "Software";
            case CONSTANTS.OFFICE_SUPPLY:
                return "Office supply";
            default:
                return "Unknown";
        }
    },

    //Resource type to img name
    resourceTypeToImageName: function(type) {
        switch (type) {
            case CONSTANTS.FOOD:
                return "food.png";
            case CONSTANTS.TOOL:
                return "tool2.png";
            case CONSTANTS.RAW_MATERIAL:
                return "raw_material.png";
            case CONSTANTS.HARDWARE:
                return "hardware.png";
            case CONSTANTS.SOFTWARE:
                return "software.png";
            case CONSTANTS.OFFICE_SUPPLY:
                return "office_supply.png";
            default:
                return "no_image.png";
        }
    },

    //Rights name
    rightsToString: function(rights) {
        switch (parseInt(rights)) {
            case CONSTANTS.SUPER_ADMIN:
                return "Super Administrator";
            case CONSTANTS.ADMIN:
                return "Administrator";
            case CONSTANTS.MODERATOR:
                return "Moderator";
            case CONSTANTS.COLLABORATOR:
                return "Collaborator";
            case CONSTANTS.VISITOR:
                return "Visitor";
            default:
                return "Unknown";
        }
    },

    //Action name
    actionToString: function(action) {
        switch (parseInt(action)) {
            case 1:
                return "Create storage";
            case 2:
                return "Create resource";
            case 3:
                return "Create group";
            case 4:
                return "Create user";
            case 5:
                return "Delete storage";
            case 6:
                return "Delete resource";
            case 7:
                return "Delete group";
            case 8:
                return "Delete user";
            case 9:
                return "Rename storage";
            case 10:
                return "Rename resource";
            case 11:
                return "Move storage";
            case 12:
                return "Move resource";
            case 13:
                return "Toggle storage of group";
            case 14:
                return "Toggle user of group";
            case 15:
                return "Rename company";
            case 16:
                return "Rename group";
            case 17:
                return "Create storage minimum";
            case 18:
                return "Create resource minimum";
            case 19:
                return "Delete storage minimum";
            case 20:
                return "Delete storage minimum";
            case 21:
                return "Edit profile";
            case 22:
                return "Edit quantity";
            default:
                return "Unknown";
        }
    },

    //Action type
    actionToType: function(action) {
        switch (parseInt(action)) {
            case 1:
            case 5:
            case 9:
            case 11:
            case 17:
            case 19:
                return CONSTANTS.STORAGE_TARGET;
            case 2:
            case 6:
            case 10:
            case 12:
            case 18:
            case 20:
            case 22:
                return CONSTANTS.RESOURCE_TARGET;
            case 3:
            case 7:
            case 16:
                return CONSTANTS.GROUP_TARGET;
            case 4:
            case 8:
            case 21:
                return CONSTANTS.USER_TARGET;
            case 15:
                return CONSTANTS.COMPANY_TARGET;
            case 13:
                return CONSTANTS.STORAGE_GROUP_TARGET;
            case 14:
                return CONSTANTS.USER_GROUP_TARGET;
            default:
                return "Unknown";
        }
    },

    //Compare storage family
    isChild(parent,child) {
        let splitParent = parent.split(".");
        let splitChild = child.split(".");

        for(let i = 0; i < splitParent.length; i++) {
            if(splitParent[i] !== splitChild[i])
                return false;
        }

        return true;
    }
};