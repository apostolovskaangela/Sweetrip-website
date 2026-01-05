# React Native API Integration Guide

This guide shows you how to integrate the Laravel API with your React Native application.

## Table of Contents
1. [Setup](#setup)
2. [API Configuration](#api-configuration)
3. [Authentication Service](#authentication-service)
4. [API Service](#api-service)
5. [Usage Examples](#usage-examples)
6. [Error Handling](#error-handling)
7. [Token Management](#token-management)

## Setup

### 1. Install Required Packages

```bash
npm install @react-native-async-storage/async-storage axios
# or
yarn add @react-native-async-storage/async-storage axios
```

### 2. Configure API Base URL

Create a configuration file for your API:

```javascript
// config/api.js
export const API_BASE_URL = __DEV__ 
  ? 'http://localhost:8000/api'  // Development
  : 'https://your-domain.com/api'; // Production

// For Android emulator, use: http://10.0.2.2:8000/api
// For iOS simulator, use: http://localhost:8000/api
// For physical device, use your computer's IP: http://192.168.1.100:8000/api
```

## API Configuration

### Create API Client

```javascript
// services/api.js
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { API_BASE_URL } from '../config/api';

// Create axios instance
const apiClient = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request interceptor to add token
apiClient.interceptors.request.use(
  async (config) => {
    const token = await AsyncStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor for error handling
apiClient.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid
      await AsyncStorage.removeItem('auth_token');
      await AsyncStorage.removeItem('user');
      // Navigate to login screen
      // You can use navigation here if needed
    }
    return Promise.reject(error);
  }
);

export default apiClient;
```

## Authentication Service

```javascript
// services/authService.js
import apiClient from './api';
import AsyncStorage from '@react-native-async-storage/async-storage';

export const authService = {
  /**
   * Login user
   * @param {string} email
   * @param {string} password
   * @returns {Promise<{user: object, token: string}>}
   */
  async login(email, password) {
    try {
      const response = await apiClient.post('/login', {
        email,
        password,
      });

      const { user, token } = response.data;

      // Store token and user data
      await AsyncStorage.setItem('auth_token', token);
      await AsyncStorage.setItem('user', JSON.stringify(user));

      return { user, token };
    } catch (error) {
      throw this.handleError(error);
    }
  },

  /**
   * Logout user
   */
  async logout() {
    try {
      await apiClient.post('/logout');
      await AsyncStorage.removeItem('auth_token');
      await AsyncStorage.removeItem('user');
    } catch (error) {
      // Even if API call fails, clear local storage
      await AsyncStorage.removeItem('auth_token');
      await AsyncStorage.removeItem('user');
      throw this.handleError(error);
    }
  },

  /**
   * Get current authenticated user
   */
  async getCurrentUser() {
    try {
      const response = await apiClient.get('/user');
      return response.data;
    } catch (error) {
      throw this.handleError(error);
    }
  },

  /**
   * Check if user is authenticated
   */
  async isAuthenticated() {
    const token = await AsyncStorage.getItem('auth_token');
    return !!token;
  },

  /**
   * Get stored user data
   */
  async getStoredUser() {
    const userJson = await AsyncStorage.getItem('user');
    return userJson ? JSON.parse(userJson) : null;
  },

  /**
   * Handle API errors
   */
  handleError(error) {
    if (error.response) {
      // Server responded with error
      return {
        message: error.response.data?.message || 'An error occurred',
        errors: error.response.data?.errors || {},
        status: error.response.status,
      };
    } else if (error.request) {
      // Request made but no response
      return {
        message: 'Network error. Please check your connection.',
        errors: {},
        status: 0,
      };
    } else {
      // Something else happened
      return {
        message: error.message || 'An unexpected error occurred',
        errors: {},
        status: 0,
      };
    }
  },
};
```

## API Service

```javascript
// services/apiService.js
import apiClient from './api';

export const apiService = {
  // Dashboard
  async getDashboard() {
    const response = await apiClient.get('/dashboard');
    return response.data;
  },

  // Driver endpoints
  async getDriverDashboard() {
    const response = await apiClient.get('/driver/dashboard');
    return response.data;
  },

  async updateTripStatus(tripId, status) {
    const response = await apiClient.post(`/driver/trips/${tripId}/status`, {
      status,
    });
    return response.data;
  },

  async uploadCMR(tripId, file) {
    const formData = new FormData();
    formData.append('cmr', {
      uri: file.uri,
      type: file.type || 'image/jpeg',
      name: file.name || 'cmr.jpg',
    });

    const response = await apiClient.post(
      `/driver/trips/${tripId}/cmr`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      }
    );
    return response.data;
  },

  // Trip endpoints
  async getTrips(page = 1) {
    const response = await apiClient.get('/trips', {
      params: { page },
    });
    return response.data;
  },

  async getTrip(tripId) {
    const response = await apiClient.get(`/trips/${tripId}`);
    return response.data;
  },

  async getTripCreateData() {
    const response = await apiClient.get('/trips/create');
    return response.data;
  },

  async createTrip(tripData) {
    const response = await apiClient.post('/trips', tripData);
    return response.data;
  },

  async updateTrip(tripId, tripData) {
    const response = await apiClient.put(`/trips/${tripId}`, tripData);
    return response.data;
  },

  async deleteTrip(tripId) {
    const response = await apiClient.delete(`/trips/${tripId}`);
    return response.data;
  },

  // Vehicle endpoints
  async getVehicles() {
    const response = await apiClient.get('/vehicles');
    return response.data;
  },

  async getVehicle(vehicleId) {
    const response = await apiClient.get(`/vehicles/${vehicleId}`);
    return response.data;
  },

  async createVehicle(vehicleData) {
    const response = await apiClient.post('/vehicles', vehicleData);
    return response.data;
  },

  async updateVehicle(vehicleId, vehicleData) {
    const response = await apiClient.put(`/vehicles/${vehicleId}`, vehicleData);
    return response.data;
  },

  async deleteVehicle(vehicleId) {
    const response = await apiClient.delete(`/vehicles/${vehicleId}`);
    return response.data;
  },

  // User management endpoints
  async getUsers() {
    const response = await apiClient.get('/users');
    return response.data;
  },

  async createUser(userData) {
    const response = await apiClient.post('/users', userData);
    return response.data;
  },

  async updateUser(userId, userData) {
    const response = await apiClient.put(`/users/${userId}`, userData);
    return response.data;
  },

  async deleteUser(userId) {
    const response = await apiClient.delete(`/users/${userId}`);
    return response.data;
  },
};
```

## Usage Examples

### Login Screen

```javascript
// screens/LoginScreen.js
import React, { useState } from 'react';
import { View, Text, TextInput, Button, Alert, ActivityIndicator } from 'react-native';
import { authService } from '../services/authService';

export default function LoginScreen({ navigation }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);

  const handleLogin = async () => {
    if (!email || !password) {
      Alert.alert('Error', 'Please enter email and password');
      return;
    }

    setLoading(true);
    try {
      const { user } = await authService.login(email, password);
      
      // Navigate based on user role
      if (user.roles.includes('driver')) {
        navigation.replace('DriverDashboard');
      } else if (user.roles.includes('manager')) {
        navigation.replace('ManagerDashboard');
      } else {
        navigation.replace('Dashboard');
      }
    } catch (error) {
      Alert.alert('Login Failed', error.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={{ padding: 20 }}>
      <TextInput
        placeholder="Email"
        value={email}
        onChangeText={setEmail}
        keyboardType="email-address"
        autoCapitalize="none"
        style={{ borderWidth: 1, padding: 10, marginBottom: 10 }}
      />
      <TextInput
        placeholder="Password"
        value={password}
        onChangeText={setPassword}
        secureTextEntry
        style={{ borderWidth: 1, padding: 10, marginBottom: 10 }}
      />
      <Button
        title={loading ? 'Logging in...' : 'Login'}
        onPress={handleLogin}
        disabled={loading}
      />
      {loading && <ActivityIndicator style={{ marginTop: 10 }} />}
    </View>
  );
}
```

### Driver Dashboard

```javascript
// screens/DriverDashboardScreen.js
import React, { useState, useEffect } from 'react';
import { View, Text, FlatList, RefreshControl, ActivityIndicator } from 'react-native';
import { apiService } from '../services/apiService';

export default function DriverDashboardScreen() {
  const [dashboardData, setDashboardData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const loadDashboard = async () => {
    try {
      const data = await apiService.getDriverDashboard();
      setDashboardData(data);
    } catch (error) {
      console.error('Error loading dashboard:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  useEffect(() => {
    loadDashboard();
  }, []);

  const onRefresh = () => {
    setRefreshing(true);
    loadDashboard();
  };

  if (loading) {
    return (
      <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
        <ActivityIndicator size="large" />
      </View>
    );
  }

  return (
    <View style={{ flex: 1, padding: 20 }}>
      <Text style={{ fontSize: 24, fontWeight: 'bold', marginBottom: 20 }}>
        Driver Dashboard
      </Text>

      {dashboardData?.stats && (
        <View style={{ marginBottom: 20 }}>
          <Text>Total Trips: {dashboardData.stats.total_trips}</Text>
          <Text>Completed: {dashboardData.stats.completed_trips}</Text>
          <Text>Pending: {dashboardData.stats.pending_trips}</Text>
        </View>
      )}

      <FlatList
        data={dashboardData?.trips || []}
        keyExtractor={(item) => item.id.toString()}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
        }
        renderItem={({ item }) => (
          <View style={{ padding: 10, borderBottomWidth: 1 }}>
            <Text style={{ fontWeight: 'bold' }}>{item.trip_number}</Text>
            <Text>{item.destination_from} → {item.destination_to}</Text>
            <Text>Status: {item.status_label}</Text>
            <Text>Date: {item.trip_date}</Text>
          </View>
        )}
      />
    </View>
  );
}
```

### Trip List Screen

```javascript
// screens/TripsScreen.js
import React, { useState, useEffect } from 'react';
import { View, Text, FlatList, Button, ActivityIndicator } from 'react-native';
import { apiService } from '../services/apiService';

export default function TripsScreen({ navigation }) {
  const [trips, setTrips] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [loading, setLoading] = useState(true);
  const [currentPage, setCurrentPage] = useState(1);

  const loadTrips = async (page = 1) => {
    try {
      const data = await apiService.getTrips(page);
      setTrips(data.trips || []);
      setPagination(data.pagination);
    } catch (error) {
      console.error('Error loading trips:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadTrips(currentPage);
  }, [currentPage]);

  if (loading) {
    return (
      <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
        <ActivityIndicator size="large" />
      </View>
    );
  }

  return (
    <View style={{ flex: 1, padding: 20 }}>
      <Button
        title="Create Trip"
        onPress={() => navigation.navigate('CreateTrip')}
      />

      <FlatList
        data={trips}
        keyExtractor={(item) => item.id.toString()}
        renderItem={({ item }) => (
          <View style={{ padding: 10, borderBottomWidth: 1 }}>
            <Text style={{ fontWeight: 'bold' }}>{item.trip_number}</Text>
            <Text>{item.destination_from} → {item.destination_to}</Text>
            <Text>Status: {item.status_label}</Text>
            <Button
              title="View Details"
              onPress={() => navigation.navigate('TripDetails', { tripId: item.id })}
            />
          </View>
        )}
      />

      {pagination && (
        <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginTop: 20 }}>
          <Button
            title="Previous"
            disabled={currentPage === 1}
            onPress={() => setCurrentPage(currentPage - 1)}
          />
          <Text>Page {currentPage} of {pagination.last_page}</Text>
          <Button
            title="Next"
            disabled={currentPage === pagination.last_page}
            onPress={() => setCurrentPage(currentPage + 1)}
          />
        </View>
      )}
    </View>
  );
}
```

### Update Trip Status

```javascript
// components/UpdateTripStatus.js
import React, { useState } from 'react';
import { View, Text, Button, Alert, Picker } from 'react-native';
import { apiService } from '../services/apiService';

export default function UpdateTripStatus({ tripId, currentStatus, onUpdate }) {
  const [status, setStatus] = useState(currentStatus);
  const [loading, setLoading] = useState(false);

  const handleUpdate = async () => {
    setLoading(true);
    try {
      await apiService.updateTripStatus(tripId, status);
      Alert.alert('Success', 'Trip status updated successfully');
      onUpdate?.();
    } catch (error) {
      Alert.alert('Error', error.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <View>
      <Text>Update Trip Status</Text>
      {/* Use a proper picker component for React Native */}
      <Button
        title="Update Status"
        onPress={handleUpdate}
        disabled={loading}
      />
    </View>
  );
}
```

### Upload CMR Document

```javascript
// components/UploadCMR.js
import React, { useState } from 'react';
import { View, Button, Alert, Image } from 'react-native';
import * as ImagePicker from 'expo-image-picker';
import { apiService } from '../services/apiService';

export default function UploadCMR({ tripId, onUpload }) {
  const [uploading, setUploading] = useState(false);

  const pickImage = async () => {
    // Request permissions
    const { status } = await ImagePicker.requestMediaLibraryPermissionsAsync();
    if (status !== 'granted') {
      Alert.alert('Permission needed', 'Please grant camera roll permissions');
      return;
    }

    // Pick image
    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.Images,
      allowsEditing: true,
      quality: 0.8,
    });

    if (!result.canceled && result.assets[0]) {
      await uploadCMR(result.assets[0]);
    }
  };

  const uploadCMR = async (file) => {
    setUploading(true);
    try {
      const response = await apiService.uploadCMR(tripId, {
        uri: file.uri,
        type: file.type || 'image/jpeg',
        name: file.fileName || 'cmr.jpg',
      });
      
      Alert.alert('Success', 'CMR uploaded successfully');
      onUpload?.(response.trip);
    } catch (error) {
      Alert.alert('Error', error.message);
    } finally {
      setUploading(false);
    }
  };

  return (
    <View>
      <Button
        title={uploading ? 'Uploading...' : 'Upload CMR'}
        onPress={pickImage}
        disabled={uploading}
      />
    </View>
  );
}
```

## Error Handling

Create a reusable error handler:

```javascript
// utils/errorHandler.js
export const handleApiError = (error, showAlert = true) => {
  let message = 'An error occurred';

  if (error.response) {
    // Server responded with error
    message = error.response.data?.message || 'Server error';
    
    // Handle validation errors
    if (error.response.data?.errors) {
      const errors = error.response.data.errors;
      const firstError = Object.values(errors)[0];
      message = Array.isArray(firstError) ? firstError[0] : firstError;
    }
  } else if (error.request) {
    message = 'Network error. Please check your connection.';
  }

  if (showAlert) {
    Alert.alert('Error', message);
  }

  return message;
};
```

## Token Management

### Check Authentication on App Start

```javascript
// App.js
import React, { useEffect, useState } from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { authService } from './services/authService';
import LoginScreen from './screens/LoginScreen';
import MainNavigator from './navigators/MainNavigator';

export default function App() {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    try {
      const authenticated = await authService.isAuthenticated();
      if (authenticated) {
        // Verify token is still valid
        await authService.getCurrentUser();
      }
      setIsAuthenticated(authenticated);
    } catch (error) {
      setIsAuthenticated(false);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return <LoadingScreen />;
  }

  return (
    <NavigationContainer>
      {isAuthenticated ? <MainNavigator /> : <LoginScreen />}
    </NavigationContainer>
  );
}
```

## Important Notes

1. **Network Configuration**:
   - For Android emulator: Use `http://10.0.2.2:8000/api`
   - For iOS simulator: Use `http://localhost:8000/api`
   - For physical devices: Use your computer's IP address (e.g., `http://192.168.1.100:8000/api`)

2. **CORS**: Make sure your Laravel backend allows requests from your React Native app. Update `config/cors.php` if needed.

3. **File Uploads**: For file uploads, use `FormData` and set `Content-Type: multipart/form-data`.

4. **Token Storage**: Use secure storage for production apps. Consider using `react-native-keychain` for better security.

5. **Error Handling**: Always handle network errors and show user-friendly messages.

6. **Loading States**: Show loading indicators during API calls for better UX.

7. **Pagination**: Implement pagination for list endpoints to improve performance.

## Testing

You can test the API endpoints using tools like:
- Postman
- Insomnia
- curl commands

Example curl command for login:
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"driver@example.com","password":"password"}'
```

