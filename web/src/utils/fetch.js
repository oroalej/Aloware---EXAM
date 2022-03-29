export const getApi = (url) => {
    return fetch(`http://127.0.0.1:8000/${url}`, {
        method: 'GET',
        mode: 'cors',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    });
};

export const postApi = async (url = '', data = {}) => {
    const response = await fetch(`http://127.0.0.1:8000/${url}`, {
        method: 'POST',
        mode: 'cors',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });

    if (!response.ok) {
        const error = await response.json();
        return Promise.reject(error);
    }

    return response.json();
};
