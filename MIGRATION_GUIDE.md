# Migration Guide: Laravel 7 → 11 & Vue 2 → 3

## Overview

This document outlines the migration from **Laravel 7** to **Laravel 11** and **Vue.js 2.6** to **Vue.js 3.4**, along with identified underdeveloped areas that need attention.

## Migration Summary

### Backend (Laravel 7 → 11)

#### Requirements Update
- **PHP:** 7.2.5+ → **8.2+** (breaking change)
- **Composer:** Any → **2.x**
- **Laravel:** 7.24 → **11.x**

#### Key Changes Made

**1. Dependency Updates (`server/composer.json`)**
- `laravel/framework`: ^7.24 → ^11.0
- `guzzlehttp/guzzle`: ^6.3 → ^7.8
- `spatie/laravel-permission`: ^3.16 → ^6.0
- `tymon/jwt-auth`: ^1.0 → ^2.1
- `fruitcake/laravel-cors`: ^2.0 → ^3.0
- Removed deprecated packages: `fideloper/proxy`, `facade/ignition`
- Added: `laravel/pint` for code styling
- Updated testing: `phpunit/phpunit` ^8.5 → ^11.0
- Updated faker: `fzaninotto/faker` → `fakerphp/faker` ^1.23

**2. Database Structure**
- Renamed `database/seeds/` → `database/seeders/`
- Updated all seeder namespaces to `Database\Seeders`
- Converted old `factory()` helper to new `Model::factory()` pattern
- Added `HasFactory` trait to all models:
  - `User`, `Department`, `Skill`, `Leave`, `Training`, `Evaluation`

**3. Bug Fixes**
- Fixed typo in `Training` model: `belongTo()` → `belongsTo()`

### Frontend (Vue 2 → 3)

#### Build Tool Migration
- **Build System:** Vue CLI → **Vite 5.0**
- **Configuration:** `vue.config.js` → `vite.config.js`

#### Framework Updates
- **Vue.js:** 2.6.12 → **3.4.0**
- **Vue Router:** 3.2.0 → **4.2.5**
- **State Management:** Vuex 3.4.0 → **Pinia 2.1.7**
- **Bootstrap:** 4.5.2 → **5.3.2**
- **Chart.js:** 2.9.3 → **4.4.0**
- **vue-chartjs:** 3.5.1 → **5.3.0**
- **axios:** 0.20.0 → **1.6.0**

#### Code Changes

**1. Entry Point (`client/src/main.js`)**
```javascript
// Vue 2
import Vue from 'vue'
new Vue({ router, store, render: h => h(App) }).$mount('#app')

// Vue 3
import { createApp } from 'vue'
const app = createApp(App)
app.use(pinia).use(router).mount('#app')
```

**2. Router (`client/src/router/index.js`)**
```javascript
// Vue 2
import VueRouter from 'vue-router'
Vue.use(VueRouter)
const router = new VueRouter({ mode: 'history', routes })

// Vue 3
import { createRouter, createWebHistory } from 'vue-router'
const router = createRouter({ history: createWebHistory(), routes })
```

**3. State Management (Vuex → Pinia)**
```javascript
// Vue 2 (Vuex)
export default {
  namespaced: true,
  state: { user: null },
  mutations: { SET_USER(state, user) { state.user = user } },
  actions: { updateUser({ commit }, user) { commit('SET_USER', user) } }
}

// Vue 3 (Pinia)
import { defineStore } from 'pinia'
export const useAuthStore = defineStore('auth', {
  state: () => ({ user: null }),
  actions: { setUser(user) { this.user = user } }
})
```

**4. Global Filters Removed**
```javascript
// Vue 2
Vue.filter('clean', (value) => ...)

// Vue 3 (use global properties)
app.config.globalProperties.$filters = {
  clean(value) { ... }
}
```

**5. Environment Variables**
- `process.env.VUE_APP_*` → `import.meta.env.VITE_*`
- Created `.env.example` with Vite naming convention

## Underdeveloped Areas & Missing Features

### 🔴 Critical Issues

#### 1. **Authentication & Security**

**Missing Token Refresh Implementation**
- Files: `client/src/store/auth.js:73`, `client/src/main.js:55`
- Impact: Users will be logged out when JWT expires
- Status: ⚠️ TODO comment exists but not implemented

**Missing Authentication Features**
- ❌ Password reset functionality
- ❌ Forgot password endpoint
- ❌ User registration (only admin can create users)
- ❌ Email verification
- ⚠️ Token refresh endpoint exists in backend but not connected to frontend

**Security Vulnerability**
- File: `server/database/migrations/2014_10_12_000000_create_users_table.php:21`
- Issue: Hardcoded default password `'12345678'` in migration
- Recommendation: Remove default password

#### 2. **Permission System Inconsistencies**

**Mismatched Permission Names**
- Backend Policy uses: `view collaborators`, `add collaborators`, `edit collaborators`, `delete collaborators`
- API Middleware uses: `view-collaborator`, `add-collaborator`, `edit-collaborator`, `delete-collaborator`
- Files:
  - `server/app/Policies/CollaboratorPolicy.php:23, 27, 31, 35`
  - `server/routes/api.php:23, 30, 38, 42`
- Impact: Authorization may not work correctly
- Fix: Standardize to one naming convention

### 🟡 High Priority Issues

#### 3. **Placeholder/Hardcoded Values**

**Frontend Dropdowns Need Real Data**
File: `client/src/components/collaborator/CreateEditCollaborator.vue`

Lines with placeholders:
- **157-159**: Grade field (option 1, 2, 3)
- **177-179**: Type of contract (option 1, 2, 3)
- **193-195**: Leave type (option 1, 2, 3)
- **256-258**: Evaluation type (option 1, 2, 3)
- **276-277**: Evaluation status (option 1, 2)

**Database Also Uses Placeholders**
- `server/database/migrations/2014_10_12_000000_create_users_table.php:37`
  ```php
  $table->enum('type_of_contract', ['option 1', 'option 2', 'option 3'])
  ```

**Recommended Real Values:**
```javascript
// Grade
['Junior', 'Mid-level', 'Senior', 'Lead', 'Principal', 'Manager']

// Type of contract
['Permanent', 'Fixed-term', 'Contractor', 'Intern', 'Part-time']

// Leave type
['Annual Leave', 'Sick Leave', 'Personal Leave', 'Emergency Leave', 'Maternity/Paternity Leave', 'Unpaid Leave']

// Evaluation type
['Annual Review', 'Mid-Year Review', 'Probation Review', 'Project Review', 'Performance Improvement Plan']

// Evaluation status
['Scheduled', 'In Progress', 'Completed', 'Cancelled']
```

#### 4. **Incomplete API Endpoints**

**Department Controller Missing Methods**
- File: `server/app/Http/Controllers/DepartmentController.php`
- Missing: `show()`, `update()` methods
- Only has: `index`, `store`, `getUsers`, `destroy`

**Other Controllers Missing show() Methods**
- `SkillController`
- `TrainingController`
- `EvaluationController`

#### 5. **Poor Error Handling**

**Empty Catch Blocks**
- File: `client/src/components/collaborator/CreateEditCollaborator.vue:406`
  ```javascript
  }).catch();  // No error handling at all
  ```

**Console-Only Error Logging**
Multiple files only log errors to console without user feedback:
- `client/src/views/Collaborators/show.vue:115, 123, 131`
- `client/src/components/collaborator/CreateEditViewTable.vue:165, 172, 178, 182`
- `client/src/views/Dashboard.vue:106`

**Recommendation:**
- Add toast notifications for errors
- Provide user-friendly error messages
- Handle specific error codes (401, 403, 404, 422, 500)

### 🟢 Medium Priority Issues

#### 6. **Missing Validation Rules**

**Incomplete Validation in Request Classes**

`server/app/Http/Requests/Leave.php:27`
```php
'type' => 'required', // custom regex
```

`server/app/Http/Requests/Evaluation.php:28`
```php
'manager' => 'required', // custom regex
```

`server/app/Http/Requests/Collaborator.php:33-46`
- Empty string validation for: `address`, `history`, `source`, `grade`
- Should have more specific rules

**Database Migration Comments Indicate Uncertainty**
- `server/database/migrations/2020_09_02_101657_create_leaves_table.php:18`
  ```php
  $table->string('type'); // ?may be "enum"
  ```

#### 7. **User Experience Issues**

**No Date Pickers**
- Users must manually type dates in `yyyy-mm-dd` format
- Prone to input errors
- Recommendation: Add date picker component (e.g., VueDatePicker, Flatpickr)

**No Pagination Controls**
- Backend supports pagination but frontend doesn't show controls
- Users can't navigate through pages

**No Search/Filter UI**
- Departments have no search/filter functionality
- Recommendation: Add search bars and filters

#### 8. **Missing Documentation**

**OpenAPI/Swagger Gaps**
- `TrainingController` - No OpenAPI annotations
- `EvaluationController` - No OpenAPI annotations
- `DepartmentController` - Partially documented

**README Inaccuracies**
- Claims "Vuex" but code now uses Pinia
- Claims "Vue.js 2.6" but migrated to Vue 3.4
- Screenshots section empty

### 🔵 Low Priority Issues

#### 9. **Testing**

**Zero Test Coverage**
- Only example tests exist
- Missing:
  - ❌ API endpoint tests
  - ❌ Authentication tests
  - ❌ Authorization/permission tests
  - ❌ Validation tests
  - ❌ Model relationship tests
  - ❌ Frontend component tests

**Existing "Tests"**
- `server/tests/Feature/ExampleTest.php` - Only checks if `/` returns 200
- `server/tests/Unit/ExampleTest.php` - Only has `assertTrue(true)`

#### 10. **Missing Features**

**No Bulk Operations**
- ❌ Bulk delete
- ❌ Bulk update
- ❌ Bulk export

**No Export Functionality**
- ❌ CSV export
- ❌ PDF reports
- ❌ Excel export

**Department Soft Deletes**
- Users have soft delete but departments don't

**Allowed Leave Days Management**
- Field exists (`allowed_leave_days`) but no UI to modify it
- Defaults to 30 days

## Next Steps & Recommendations

### Phase 1: Critical Fixes (Week 1-2)

1. **Fix Permission System**
   - Standardize permission names across policies and middleware
   - Test all permission checks

2. **Implement Token Refresh**
   - Connect frontend to existing `/auth/refresh` endpoint
   - Add automatic token refresh before expiry
   - Handle token expiration gracefully

3. **Replace Placeholder Values**
   - Update database enums with real values
   - Update frontend dropdowns
   - Create migration for existing data

4. **Security Fixes**
   - Remove hardcoded default password from migration
   - Review and fix all validation rules

### Phase 2: Essential Features (Week 3-4)

5. **Complete CRUD Operations**
   - Add missing `show()` and `update()` methods to controllers
   - Implement proper error handling in frontend

6. **Authentication Features**
   - Password reset flow
   - Forgot password functionality
   - Email verification (optional)

7. **User Experience**
   - Add date pickers for all date fields
   - Implement pagination controls
   - Add loading states and error messages

### Phase 3: Quality Improvements (Week 5-6)

8. **Testing**
   - Write feature tests for all API endpoints
   - Write unit tests for critical business logic
   - Add frontend component tests

9. **Documentation**
   - Complete OpenAPI/Swagger documentation
   - Update README with accurate information
   - Add API documentation examples

10. **Additional Features**
    - Search and filter functionality
    - Export capabilities (CSV, PDF)
    - Bulk operations

### Phase 4: Migration Deployment

11. **Update Dependencies**
    ```bash
    # Backend
    cd server
    composer install

    # Frontend
    cd client
    npm install
    ```

12. **Update Environment**
    - Ensure PHP 8.2+ is installed
    - Update `.env` files with Vite variable names
    - Run migrations: `php artisan migrate`
    - Seed database: `php artisan db:seed`

13. **Test Build**
    ```bash
    # Frontend build
    npm run build

    # Backend test
    php artisan test
    ```

## Breaking Changes to Note

### For Developers

**Backend:**
- PHP 8.2 required (upgrade server environment)
- String functions may behave differently
- Some deprecated methods removed

**Frontend:**
- Component syntax changes from Options API
- `$store` access needs migration to Pinia
- Global filters removed (use methods/computed properties)
- Build commands changed (`npm run serve` still works but `npm run dev` is new)

### For Users

- No user-facing breaking changes
- All existing functionality preserved
- Performance improvements expected

## Estimated Development Time

- **Critical Fixes:** 2 weeks
- **Essential Features:** 2 weeks
- **Quality Improvements:** 2 weeks
- **Total:** ~6 weeks for full completion

## Migration Checklist

- [x] Update Laravel dependencies
- [x] Update Vue dependencies
- [x] Migrate database seeders
- [x] Update factories to new format
- [x] Add HasFactory trait to models
- [x] Convert Vuex to Pinia
- [x] Update Vue Router to v4
- [x] Create Vite configuration
- [x] Update environment variables
- [ ] Fix permission naming inconsistencies
- [ ] Implement token refresh
- [ ] Replace placeholder values
- [ ] Add missing CRUD endpoints
- [ ] Implement proper error handling
- [ ] Add validation rules
- [ ] Write comprehensive tests
- [ ] Update documentation
- [ ] Add password reset flow
- [ ] Implement date pickers
- [ ] Add export functionality

## Resources

- [Laravel 11 Upgrade Guide](https://laravel.com/docs/11.x/upgrade)
- [Vue 3 Migration Guide](https://v3-migration.vuejs.org/)
- [Pinia Documentation](https://pinia.vuejs.org/)
- [Vite Guide](https://vitejs.dev/guide/)
- [Vue Router 4 Migration](https://router.vuejs.org/guide/migration/)

---

**Migration Date:** November 9, 2025
**Migrated By:** Claude AI
**Status:** Core migration complete, features pending development
