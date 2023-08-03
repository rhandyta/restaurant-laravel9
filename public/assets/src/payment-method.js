const formpayment = document.querySelector('#formpayment');
const formpayment_edit = document.querySelector('#formpayment_edit');
const data = {};

async function __onSubmitHandler(event) {
    event.preventDefault()
   try {
    const formData = new FormData(formpayment)
    const request = await fetch(`${SEGMENT_URL}`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken
        },
        body: formData
    })

    const response = await request.json()
    if(response.status_code == 422) {
        Object.values(response.messages).forEach((item) => {
            throw new Error(item)
        })
    };
    if(response.status_code != 201) throw Error('something went wrong');
    successToast(response.messages, 1000);
        return setTimeout(() => {
            window.location.reload();
        }, 1000);

   } catch(e) {
    errorToast(e)
   }
}

async function __onSubmitUpdateHandler (event) {
    event.preventDefault();
    try {
        const formData = new FormData(formpayment_edit);
        formData.append('_method', 'PATCH')
        const request = await fetch(`${SEGMENT_URL}/${data.id}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken
            },
            body: formData
        })
        const response = await request.json();
        if(response.status_code == 422) {
            Object.values(response.messages).forEach(item => {
                throw new Error(item)
            })
        }
        if(response.status_code !== 200) throw new Error('something went wrong')
        successToast(response.messages, 1000);
        return setTimeout(() => {
            window.location.reload();
        }, 1000);
    } catch(e) {
        return errorToast(e)
    }
}

async function __getDataById() {
    try {
        const request = await fetch(`${SEGMENT_URL}/${this.getAttribute('data-id')}`)
        const response = await request.json();
        if(response.status_code !== 200) throw new Error(response.messages)
        formpayment_edit.id.value = response.data.id
        formpayment_edit.payment_type.value = response.data.payment_type
        formpayment_edit.status.value = response.data.status
        return Object.assign(data, response.data)
    } catch(e) {
        return errorToast(e)
    }
}

document.addEventListener('DOMContentLoaded', () => {

    formpayment.addEventListener('submit', __onSubmitHandler)

    document.querySelectorAll('.btn-edit').forEach((item) => {
        item.addEventListener('click', __getDataById)
    })
    formpayment_edit.addEventListener('submit', __onSubmitUpdateHandler)



    return () => {
        formpayment.removeEventListener('submit', __onSubmitHandler)
        formpayment_edit.removeEventListener('submit', __onSubmitUpdateHandler)
        document.querySelectorAll('.btn-edit').forEach((item) => {
            item.addEventListener('click', __getDataById)
        })
    }
});
