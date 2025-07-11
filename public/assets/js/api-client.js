// API Client for FSM Platform
class ApiClient {
    constructor() {
        this.baseUrl = window.location.origin + '/fsm/public/api';
        this.token = localStorage.getItem('authToken');
    }

    // Set auth token
    setToken(token) {
        this.token = token;
        localStorage.setItem('authToken', token);
    }

    // Clear auth token
    clearToken() {
        this.token = null;
        localStorage.removeItem('authToken');
        sessionStorage.removeItem('currentUser');
    }

    // Make API request
    async request(endpoint, options = {}) {
        const url = `${this.baseUrl}/${endpoint}`;
        
        const defaultOptions = {
            headers: {
                'Accept': 'application/json',
            }
        };

        // Add auth token if available
        if (this.token) {
            defaultOptions.headers['Authorization'] = `Bearer ${this.token}`;
            defaultOptions.headers['X-API-Token'] = this.token; // Fallback
        }

        // Add content-type for JSON requests
        if (options.body && !(options.body instanceof FormData)) {
            defaultOptions.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(options.body);
        }

        const finalOptions = { ...defaultOptions, ...options };
        finalOptions.headers = { ...defaultOptions.headers, ...options.headers };

        try {
            const response = await fetch(url, finalOptions);
            const data = await response.json();

            if (!response.ok) {
                if (response.status === 401) {
                    // Unauthorized - redirect to login
                    this.clearToken();
                    window.location.href = '/fsm/public/login';
                    throw new Error('Unauthorized');
                }
                throw new Error(data.message || 'API request failed');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    // Convenience methods
    get(endpoint) {
        return this.request(endpoint, { method: 'GET' });
    }

    post(endpoint, data) {
        return this.request(endpoint, { method: 'POST', body: data });
    }

    put(endpoint, data) {
        return this.request(endpoint, { method: 'PUT', body: data });
    }

    delete(endpoint) {
        return this.request(endpoint, { method: 'DELETE' });
    }

    // Auth methods
    async login(username, password) {
        const formData = new FormData();
        formData.append('username', username);
        formData.append('password', password);
        
        const response = await this.request('auth/login', {
            method: 'POST',
            body: formData
        });
        
        if (response.status === 'success') {
            this.setToken(response.data.token);
            sessionStorage.setItem('currentUser', JSON.stringify(response.data.user));
        }
        
        return response;
    }

    async logout() {
        try {
            await this.post('auth/logout');
        } finally {
            this.clearToken();
            window.location.href = '/fsm/public/login';
        }
    }

    // Customer methods
    async getCustomers(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return this.get(`customers${queryString ? '?' + queryString : ''}`);
    }

    async getCustomer(id) {
        return this.get(`customers/${id}`);
    }

    async createCustomer(data) {
        return this.post('customers', data);
    }

    async updateCustomer(id, data) {
        return this.put(`customers/${id}`, data);
    }

    async deleteCustomer(id) {
        return this.delete(`customers/${id}`);
    }

    async getNearbyCustomers(lat, lng, radius = 10) {
        return this.get(`customers/nearby?lat=${lat}&lng=${lng}&radius=${radius}`);
    }
}

// Create global instance
window.apiClient = new ApiClient();
