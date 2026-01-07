function subscription_discount(price, discount, d_type = 'amount') {
    if (d_type === 'percent') {
        return parseInt(price) - (parseInt(price) * parseInt(discount) / 100);
    } else {
        return parseInt(price) - parseInt(discount);
    }
}

function currencySymbolsuffix(amount, symbol = 'INR') {
    let icon = { 'USD': '$', 'INR': '₹' };
    return icon[symbol] + ' ' + amount;
}

function formatDate(timestamp) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const date = new Date(timestamp);
    const day = String(date.getDate()).padStart(2, '0');
    const month = months[date.getMonth()];
    const year = String(date.getFullYear()).substr(-2);
    const hour = String(date.getHours()).padStart(2, '0');
    const minute = String(date.getMinutes()).padStart(2, '0');
    const second = String(date.getSeconds()).padStart(2, '0');
    const period = hour >= 12 ? 'PM' : 'AM';
    const formattedHour = hour % 12 || 12;
    return `${day} ${month} ${year}, ${formattedHour}:${minute}:${second} ${period}`;
}

function readImage(input,selector) {
    try{
        const reader = new FileReader();
        reader.onload = function (e) {
            const imgSrc = e.target.result;
            document.querySelector(selector).src = imgSrc;
        };
        reader.readAsDataURL(input.files[0]);
    }catch(error){
        console.error(error);
    }
    
}

// to validate form each form element is required to have
// data-validate = true
// data-regx = /regx/
function validateForm(selector) {
    try {
        let invalidCount = 0;
        let errMessages = [];
        const form = document.querySelector(selector);
        if (form) {
            form.querySelectorAll("input,textarea").forEach(formElement => {
                if (formElement.classList.contains('is-invalid')) {
                    formElement.classList.remove("is-valid");
                }

                if (formElement.dataset.validate === "true") {
                    let validateMessage = "";
                    let label = form.querySelector(`label[for="${formElement.id}"]`);
                    if (formElement.value.trim().length < 1) {
                        if (!formElement.classList.contains('is-invalid')) {
                            formElement.classList.add("is-invalid");
                        }
                        if (label) {
                            validateMessage = `${label.textContent} is required`;
                        } else if (formElement.placeholder) {
                            validateMessage = `${formElement.placeholder} is required`;
                        }
                        errMessages.push(validateMessage);
                        invalidCount++;
                    } else if (formElement.dataset.regx) {
                        const regx = new RegExp(formElement.dataset.regx);
                        if (!regx.test(formElement.value)) {
                            if (!formElement.classList.contains('is-invalid')) {
                                formElement.classList.add("is-invalid");
                            }
                            if (label) {
                                validateMessage = `${label.textContent} isn't in valid format`;
                            } else if (formElement.placeholder) {
                                validateMessage = `${formElement.placeholder} isn't in valid format`;
                            }
                            errMessages.push(validateMessage);
                            invalidCount++;
                        } else {
                            if (formElement.classList.contains('is-invalid')) {
                                formElement.classList.remove("is-invalid");
                            }
                            if (!formElement.classList.contains('is-valid')) {
                                formElement.classList.add("is-valid");
                            }
                        }
                    }else{
                        if (formElement.classList.contains('is-invalid')) {
                            formElement.classList.remove("is-invalid");
                        }
                        if (!formElement.classList.contains('is-valid')) {
                            formElement.classList.add("is-valid");
                        }
                    }
                }
            });
            if (invalidCount > 0) {
                throw new Error(`<ul>${errMessages.map(message => `<li>${message}</li>`).join('')}</ul>`);
            }
            return true;
        }
    } catch (error) {
        console.error(error);
        // data.success,"Success!",{closeButton:!0,tapToDismiss:!1,progressBar:!0}
        toastr.error(error.message,"Error!",{closeButton:!0,tapToDismiss:!1,timeOut:10000});
        return false;
    }
}

function timeStringToMinutes(timeString) {
    const [hours, minutes, seconds] = timeString.split(':').map(Number);
    return hours * 60 + minutes + Math.round(seconds / 60);
}

function escapeLiterals(inputString) {
    let newtring =  inputString.replace(/\\/g, '\\\\');
     newtring =  newtring.replace(/\</g, '\\<');
    return newtring.replace(/\>/g, '\\>');
}




       
export { subscription_discount, currencySymbolsuffix, formatDate ,readImage,validateForm,escapeLiterals, timeStringToMinutes};