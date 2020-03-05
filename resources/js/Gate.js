export default class Gate {
    constructor(user) {
        this.user = user;
    }

    isAdmin() {
        return this.user.type === "Admin";
    }

    isExpert() {
        return this.user.type === "Expert";
    }

    isFarmer() {
        return this.user.type === "Farmer";
    }

    isSupplier() {
        return this.user.type === "Supplier";
    }

    isFarmerOrSupplier() {
        if (this.user.type === "admin" || this.user.type === "author") {
            return true;
        }
    }

  /*   isAdminOrAuthor() {
        if (this.user.type === "admin" || this.user.type === "author") {
            return true;
        }
    }
    isAuthorOrUser() {
        if (this.user.type === "user" || this.user.type === "author") {
            return true;
        }
    } */
}
