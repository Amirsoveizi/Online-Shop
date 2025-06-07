import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
// import api from '@/services/api'

export const useAuthStore = defineStore('auth', {

  isFieldFocusRegistered : ref(false),

  state: () => ({
    user: null, // Will hold { name, email, role }
    token: localStorage.getItem('token') || null,
  }),

  getters: {
    isLoggedIn: (state) => !!state.user,
    isAdmin: (state) => state.user?.role === 'admin',
  },

  actions: {
    async loginUser(email, password) {
      // const response = await api.post('/login', { email, password });
      this.setUserData({
        user: { name: 'John Doe', email, role: 'user' },
        token: 'fake-jwt' + password
      });
    },

    async loginAdmin(email, password) {
      // const response = await api.post('/admin/login', { email, password });

      this.setUserData({
          user: { name: 'Admin User', email, role: 'admin' },
          token: 'fake-admin-jwt'+ password
      });
    },

    setUserData(data) {
        this.user = data.user;
        this.token = data.token;
        localStorage.setItem('token', data.token);
        // api.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
    },

    logout() {
      this.user = null;
      this.token = null;
      localStorage.removeItem('token');
      // delete api.defaults.headers.common['Authorization'];
    }
  }
})
