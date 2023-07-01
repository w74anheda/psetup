import { setLocale, addMethod, string } from "yup";

export default defineNuxtPlugin((nuxtApp) => {
    setLocale({
        mixed: {
            required: "لطفا ${path} را وارد نمایید.",
        },
        string: {
            email: "ایمیل نامعتبر است.",
            min: "${label} باید حداقل ${min} باشد",
            max: "${label} باید حداکثر ${max} باشد",
        },
        number: {
            min: "${label} باید حداقل ${min} باشد",
            max: "${label} باید حداکثر ${max} باشد",
        },
    });
    addMethod(string, "phone", function phoneNumber() {
        return this.test(
            "phone",
            "شماره موبایل نامعتبر می باشد.",
            function (value) {
                if (value === undefined) return true;
                return phoneNumberValidator(value.toString());
            },
        ).matches(
            /09(1[0-9]|3[1-9]|2[1-9])-?[0-9]{3}-?[0-9]{4}/,
            "شماره موبایل را به شکل صحیح وارد نمایید."
          )
    });
});
