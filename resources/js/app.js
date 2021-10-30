require("./bootstrap");

import Swal from "sweetalert2";

window.swal = function (parameters, callback = null) {
    Swal.fire(parameters).then(({ isConfirmed }) => {
        if (isConfirmed && callback !== null) {
            callback();
        }
    });
};
