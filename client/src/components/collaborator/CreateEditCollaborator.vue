<template>
    <div class="p-2">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-4 text-center bg-primary text-shadow">
                <h1 class="text-light display-4 mb-0">
                    <template v-if="role === 'create'">Create collaborator</template>
                    <template v-else>Edit <span class="text-warning">{{ collaboratorName }}</span>'s informations</template>
                </h1>
                <h6 v-if="role === 'create'" class="text-warning lead font-weight-normal">Fill out the form below</h6>
            </div>
            <form autocomplete="off" class="p-4" @submit.prevent="onSubmit()" @keydown="mainForm.errors.clear($event.target.name)">
                <h3 class="p-2 mb-0">Contact</h3>
                <div class="grid-container mb-4">
                    <div class="grid">
                        <label class="required" for="#">Full name</label>
                        <input class="form-control" name="name" type="text" placeholder="John Doe" v-model="mainForm.name" :class="{ 'is-invalid': mainForm.errors.has('name') }">
                        <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('name')" v-text="mainForm.errors.get('name')"></p>
                    </div>
                    <!-- everyone of those may be a component instead -->
                    <div class="grid">
                        <label class="required" for="#">Email address</label>
                        <input class="form-control" type="email" placeholder="johndoe@example.com" name="email" v-model="mainForm.email" :class="{ 'is-invalid': mainForm.errors.has('email') }">
                        <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('email')" v-text="mainForm.errors.get('email')"></p>
                    </div>
                    <div class="grid">
                        <label class="required" for="#">Phone number</label>
                        <input class="form-control" type="text" placeholder="(+216) 50 123 123" name="phone_number" v-model="mainForm.phone_number" :class="{ 'is-invalid': mainForm.errors.has('phone_number') }">
                        <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('phone_number')" v-text="mainForm.errors.get('phone_number')"></p>
                    </div>
                    <div class="grid">
                        <label for="username">Username</label>
                        <input class="form-control" type="text" name="username" id="username" v-model="mainForm.username" placeholder="johndoe" :class="{ 'is-invalid': mainForm.errors.has('username') }">
                        <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('username')" v-text="mainForm.errors.get('username')"></p>
                    </div>
                    <div class="grid">
                        <label for="password">Password</label>
                        <input class="form-control" type="password" name="password" id="password" v-model="mainForm.password" :class="{ 'is-invalid': mainForm.errors.has('password') }">
                        <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('password')" v-text="mainForm.errors.get('password')"></p>
                    </div>
                    <div class="grid">
                        <label for="image">{{ role === 'create' ? 'Set' : 'Change'}} profile image</label>
                        <input type="file" name="profileImage" class="form-control-file mt-1" id="image" @change="onImageChange">
                    </div>
                </div>
                <div class="custom-grid-container">
                    <div class="grid p-2">
                        <div class="list-group rounded small shadow" id="list-tab" role="tablist">
                            <a class="list-group-item list-group-item-action py-3 active" id="list-informations-list" data-toggle="list" href="#list-informations" role="tab" aria-controls="informations"><i class="fas fa-id-card-alt fa-lg"></i>Informations</a>
                            <a class="list-group-item list-group-item-action py-3" id="list-contract-list" data-toggle="list" href="#list-contract" role="tab" aria-controls="contract"><i class="fas fa-file-signature fa-lg"></i>Employment contract</a>
                            <a class="list-group-item list-group-item-action py-3" id="list-leave-list" data-toggle="list" href="#list-leave" role="tab" aria-controls="leave"><i class="fas fa-tree fa-lg"></i>Leaves counter</a>
                            <a class="list-group-item list-group-item-action py-3" id="list-skills-list" data-toggle="list" href="#list-skills" role="tab" aria-controls="skills"><i class="fas fa-star fa-lg"></i>Skills</a>
                            <a class="list-group-item list-group-item-action py-3" id="list-training-list" data-toggle="list" href="#list-training" role="tab" aria-controls="training"><i class="fas fa-user-graduate fa-lg"></i>Trainings</a>
                            <a class="list-group-item list-group-item-action py-3" id="list-evaluations-list" data-toggle="list" href="#list-evaluations" role="tab" aria-controls="evaluations"><i class="fas fa-calculator fa-lg"></i>Evaluations</a>
                        </div>
                    </div>
                    <div class="grid p-2">
                        <div class="p-4 shadow-sm border border-light rounded-lg">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="list-informations" role="tabpanel" aria-labelledby="list-informations-list">
                                    <div class="mb-5">
                                        <h4>Personal Informations</h4>
                                        <div class="grid-container-sm">
                                            <div class="grid">
                                                <label for="#">Date of birth <span>(yyyy-mm-dd)</span></label>
                                                <input type="text" placeholder="yyyy-mm-dd" class="form-control" name="date_of_birth" v-model="mainForm.date_of_birth" :class="{ 'is-invalid': mainForm.errors.has('date_of_birth') }">
                                                <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('date_of_birth')" v-text="mainForm.errors.get('date_of_birth')"></p>
                                            </div>
                                            <!-- may be a component -->
                                             <div class="grid">
                                                <label for="#">Address</label>
                                                <input type="text" class="form-control" name="address" v-model="mainForm.address" :class="{ 'is-invalid': mainForm.errors.has('address') }">
                                                <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('address')" v-text="mainForm.errors.get('address')"></p>
                                            </div>
                                            <div class="grid">
                                                <label class="required" for="civil_status">Civil status</label>
                                                <select class="custom-select" name="civil_status" id="civil_status" v-model="mainForm.civil_status" :class="{ 'is-invalid': mainForm.errors.has('civil_status') }" @change="mainForm.errors.clear('civil_status')">
                                                    <option selected value="">Select</option>
                                                    <option value="single">Single</option>
                                                    <option value="married">Married</option>
                                                </select>
                                                <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('civil_status')" v-text="mainForm.errors.get('civil_status')"></p>
                                            </div>
                                            <div class="grid">
                                                <label class="required" for="#">Gender</label>
                                                <select class="custom-select" name="gender" v-model="mainForm.gender" :class="{ 'is-invalid': mainForm.errors.has('gender') }" @change="mainForm.errors.clear('gender')">
                                                    <option selected value="">Select</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                                <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('gender')" v-text="mainForm.errors.get('gender')"></p>
                                            </div>
                                            <div class="grid">
                                                <label class="required" for="#">ID card number</label>
                                                <input type="text" class="form-control" placeholder="12345678" name="id_card_number" v-model="mainForm.id_card_number" :class="{ 'is-invalid': mainForm.errors.has('id_card_number') }">
                                                <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('id_card_number')" v-text="mainForm.errors.get('id_card_number')"></p>
                                            </div>
                                            <div class="grid">
                                                <label for="#">Nationality</label>
                                                <input type="text" class="form-control" name="nationality" v-model="mainForm.nationality" :class="{ 'is-invalid': mainForm.errors.has('nationality') }">
                                                <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('nationality')" v-text="mainForm.errors.get('nationality')"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h4>HR Informations</h4>
                                        <div class="grid-container-sm">
                                            <div class="grid">
                                                <label for="#">University</label>
                                                <input type="text" class="form-control" name="university" v-model="mainForm.university" :class="{ 'is-invalid': mainForm.errors.has('university') }">
                                                <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('university')" v-text="mainForm.errors.get('nationality')"></p>
                                            </div>
                                            <div class="grid">
                                                <label for="history">History</label>
                                                <input type="text" class="form-control" name="history" id="history" v-model="mainForm.history" :class="{ 'is-invalid': mainForm.errors.has('history') }">
                                                <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('history')" v-text="mainForm.errors.get('history')"></p>
                                            </div>
                                            <div class="grid">
                                                <label for="experience_level">Experience level</label>
                                                <select class="custom-select" name="experience_level" id="experience_level" v-model="mainForm.experience_level" :class="{ 'is-invalid': mainForm.errors.has('experience_level') }">
                                                    <option selected value="">Select</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                                <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('experience_level')" v-text="mainForm.errors.get('experience_level')"></p>
                                            </div>
                                            <div class="grid">
                                                <label for="source">Source</label>
                                                <input type="text" class="form-control" name="source" id="source" v-model="mainForm.source" :class="{ 'is-invalid': mainForm.errors.has('source') }">
                                                <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('source')" v-text="mainForm.errors.get('source')"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="list-contract" role="tabpanel" aria-labelledby="list-contract-list">
                                    <h4>Contractual information</h4>
                                    <div class="grid-container-sm">
                                        <div class="grid">
                                            <label class="required" for="department">Department</label>
                                            <select class="custom-select" name="department_id" id="department" v-model="mainForm.department_id" :class="{ 'is-invalid': mainForm.errors.has('department_id') }">
                                                <option selected value="">Select</option>
                                                <option v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                                            </select>
                                            <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('department_id')" v-text="mainForm.errors.get('department_id')"></p>
                                        </div>
                                        <div class="grid">
                                            <label class="required" for="position">Position</label>
                                            <input type="text" class="form-control" name="position" id="position" v-model="mainForm.position" :class="{ 'is-invalid': mainForm.errors.has('position') }">
                                            <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('position')" v-text="mainForm.errors.get('position')"></p>
                                        </div>
                                        <div class="grid">
                                            <label for="grade">Grade</label>
                                            <select class="custom-select" name="grade" id="grade" v-model="mainForm.grade" :class="{ 'is-invalid': mainForm.errors.has('grade') }">
                                                <option selected value="">Select</option>
                                                <option v-for="grade in grades" :key="grade" :value="grade">{{ grade }}</option>
                                            </select>
                                            <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('grade')" v-text="mainForm.errors.get('grade')"></p>
                                        </div>
                                        <div class="grid">
                                            <label for="hiring_date">Hiring date <span>(yyyy-mm-dd)</span></label>
                                            <input type="text" placeholder="yyyy-mm-dd" class="form-control" name="hiring_date" id="hiring_date" v-model="mainForm.hiring_date" :class="{ 'is-invalid': mainForm.errors.has('hiring_date') }">
                                            <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('hiring_date')" v-text="mainForm.errors.get('hiring_date')"></p>
                                        </div>
                                        <div class="grid">
                                            <label for="contract_end_date">Contract end date <span>(yyyy-mm-dd)</span></label>
                                            <input type="text" placeholder="yyyy-mm-dd" class="form-control" name="contract_end_date" id="contract_end_date" v-model="mainForm.contract_end_date" :class="{ 'is-invalid': mainForm.errors.has('contract_start_date') }">
                                            <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('contract_start_date')" v-text="mainForm.errors.get('contract_start_date')"></p>
                                        </div>
                                        <div class="grid">
                                            <label for="type">Type of contract</label>
                                            <select class="custom-select" name="type_of_contract" id="type_of_contract" v-model="mainForm.type_of_contract" :class="{ 'is-invalid': mainForm.errors.has('type_of_contract') }">
                                                <option selected value="">Select</option>
                                                <option v-for="type in contractTypes" :key="type" :value="type">{{ type }}</option>
                                            </select>
                                            <p class="text-danger mt-1 mb-0 small" v-if="mainForm.errors.has('type_of_contract')" v-text="mainForm.errors.get('type_of_contract')"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- end 1st Form -->
                                <div class="tab-pane fade" id="list-leave" role="tabpanel" aria-labelledby="list-leave-list">
                                    <Table ref="leavesSection" :name="'leave'" :form="newLeaveForm">
                                        <template slot="modal-body">
                                            <div class="form-group">
                                                <label for="#">Type</label>
                                                <select class="custom-select" :class="{ 'is-invalid': newLeaveForm.errors.has('type') }" name="type" @change="newLeaveForm.errors.clear('type')" v-model="newLeaveForm.type">
                                                    <option selected value="">Select</option>
                                                    <option v-for="type in leaveTypes" :key="type" :value="type">{{ type }}</option>
                                                </select>
                                                <p class="text-danger mt-1 mb-0 small" v-if="newLeaveForm.errors.has('type')">{{ newLeaveForm.errors.get('type') }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Days <span>(number)</span></label>
                                                <input type="number" name="days" class="form-control" :class="{ 'is-invalid': newLeaveForm.errors.has('days') }" v-model="newLeaveForm.days">
                                                <p class="text-danger mt-1 mb-0 small" v-if="newLeaveForm.errors.has('days')">{{ newLeaveForm.errors.get('days') }}</p>
                                            </div>
                                        </template>
                                    </Table>
                                </div>
                                <div class="tab-pane fade" id="list-skills" role="tabpanel" aria-labelledby="list-skills-list">
                                    <Table ref="skillsSection" :name="'skill'" :form="newSkillForm">
                                        <template slot="modal-body">
                                            <div class="form-group">
                                                <label for="#">Name</label>
                                                <input type="text" class="form-control" name="name" v-model="newSkillForm.name" :class="{ 'is-invalid': newSkillForm.errors.has('name') }" placeholder="e.g. Laravel">
                                                <p class="text-danger mt-1 mb-0 small" v-if="newSkillForm.errors.has('name')">{{ newSkillForm.errors.get('name') }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Note <span>(max: 10)</span></label>
                                                <input type="number" class="form-control" name="note" v-model="newSkillForm.note" :class="{ 'is-invalid': newSkillForm.errors.has('note') }">
                                                <p class="text-danger mt-1 mb-0 small" v-if="newSkillForm.errors.has('note')">{{ newSkillForm.errors.get('note') }}</p>
                                            </div>
                                        </template>
                                    </Table>
                                </div>
                                <div class="tab-pane fade" id="list-training" role="tabpanel" aria-labelledby="list-training-list">
                                    <Table ref="trainingsSection" :name="'training'" :form="newTrainingForm">
                                        <template slot="modal-body">
                                            <div class="form-group">
                                                <label for="#">Entitled</label>
                                                <input type="text" name="entitled" class="form-control" :class="{ 'is-invalid': newTrainingForm.errors.has('entitled') }" v-model="newTrainingForm.entitled">
                                                <p class="text-danger mt-1 mb-0 small" v-if="newTrainingForm.errors.has('entitled')">{{ newTrainingForm.errors.get('entitled') }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Start date <span>(yyyy-mm-dd)</span></label>
                                                <input type="text" placeholder="yyyy-mm-dd" name="start_date" class="form-control" :class="{ 'is-invalid': newTrainingForm.errors.has('start_date') }" v-model="newTrainingForm.start_date">
                                                <p class="text-danger mt-1 mb-0 small" v-if="newTrainingForm.errors.has('start_date')">{{ newTrainingForm.errors.get('start_date') }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="#">Duration <span>(number of hours)</span></label>
                                                <input type="number" name="duration" class="form-control" :class="{ 'is-invalid': newTrainingForm.errors.has('duration') }" placeholder="e.g. 5" v-model="newTrainingForm.duration">
                                                <p class="text-danger mt-1 mb-0 small" v-if="newTrainingForm.errors.has('duration')">{{ newTrainingForm.errors.get('duration') }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Note <span>(max: 10)</span></label>
                                                <input type="number" name="note" placeholder="e.g. 5" class="form-control" :class="{ 'is-invalid': newTrainingForm.errors.has('note') }" v-model="newTrainingForm.note">
                                                <p class="text-danger mt-1 mb-0 small" v-if="newTrainingForm.errors.has('note')">{{ newTrainingForm.errors.get('note') }}</p>
                                            </div>
                                        </template>
                                    </Table>
                                </div>
                                <div class="tab-pane fade" id="list-evaluations" role="tabpanel" aria-labelledby="list-evaluations-list">
                                    <Table ref="evaluationsSection" :name="'evaluation'" :form="newEvaluationForm">
                                        <template slot="modal-body">
                                            <div class="form-group">
                                                <label for="#">Type</label>
                                                <select name="type" @change="newEvaluationForm.errors.clear('type')" class="custom-select" :class="{ 'is-invalid': newEvaluationForm.errors.has('type') }" v-model="newEvaluationForm.type">
                                                    <option selected value="">Select</option>
                                                    <option v-for="type in evaluationTypes" :key="type" :value="type">{{ type }}</option>
                                                </select>
                                                <p class="text-danger mt-1 mb-0 small" v-if="newEvaluationForm.errors.has('type')">{{ newEvaluationForm.errors.get('type') }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Manager</label>
                                                <input type="text" name="manager" placeholder="e.g. John Doe" class="form-control" :class="{ 'is-invalid': newEvaluationForm.errors.has('manager') }" v-model="newEvaluationForm.manager">
                                                <p class="text-danger mt-1 mb-0 small" v-if="newEvaluationForm.errors.has('manager')">{{ newEvaluationForm.errors.get('manager') }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Date <span>(yyyy-mm-dd)</span></label>
                                                <input type="text" placeholder="yyyy-mm-dd" name="date" class="form-control" :class="{ 'is-invalid': newEvaluationForm.errors.has('date') }" v-model="newEvaluationForm.date">
                                                <p class="text-danger mt-1 mb-0 small" v-if="newEvaluationForm.errors.has('date')">{{ newEvaluationForm.errors.get('date') }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="#">Status</label>
                                                <select name="status" @change="newEvaluationForm.errors.clear('status')" class="custom-select" :class="{ 'is-invalid': newEvaluationForm.errors.has('status') }" v-model="newEvaluationForm.status">
                                                    <option selected value="">Select</option>
                                                    <option v-for="status in evaluationStatuses" :key="status" :value="status">{{ status }}</option>
                                                </select>
                                                <p class="text-danger mt-1 mb-0 small" v-if="newEvaluationForm.errors.has('status')">{{ newEvaluationForm.errors.get('status') }}</p>
                                            </div>
                                        </template>
                                    </Table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-sm-flex text-center justify-content-center mt-4">
                    <button type="submit" class="btn btn-sm btn-success px-4 py-3 rounded-pill mb-1 mb-sm-0" :class="{ 'mr-1': role === 'edit' }">{{ role === 'edit' ? 'Update' : 'Save' }} Collaborator</button>
                    <button v-if="role === 'edit'" type="button" class="btn btn-sm btn-danger px-4 py-3 rounded-pill" @click="deleteCollaborator">Delete Collaborator</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Form from '@/core/Form';
import { GRADES, CONTRACT_TYPES, LEAVE_TYPES, EVALUATION_TYPES, EVALUATION_STATUSES } from '@/constants/dropdowns';

export default {
    props: {
        role: null
    },
    components: {
        Table: () => {
            return import('@/components/collaborator/CreateEditViewTable');
        },
    },
    data() {
        return {
            // collaborator: Object,
            departments: [],

            // Dropdown options
            grades: GRADES,
            contractTypes: CONTRACT_TYPES,
            leaveTypes: LEAVE_TYPES,
            evaluationTypes: EVALUATION_TYPES,
            evaluationStatuses: EVALUATION_STATUSES,

            newLeaveForm: new Form({
                type: null,
                days: null
            }),
            newSkillForm: new Form({
                name: null,
                note: null
            }),
            newTrainingForm: new Form({
                entitled: null,
                start_date: null,
                duration: null,
                note: null,
            }),
            newEvaluationForm: new Form({
                type: null,
                manager: null,
                date: null,
                status: null
            }),
            mainForm: new Form({
                name: null,
                username: null,
                email: null,
                password: null,
                phone_number: null,
                date_of_birth: null,
                address: null,
                civil_status: null,
                gender: null,
                id_card_number: null,
                nationality: null,
                university: null,
                history: null,
                experience_level: null,
                source: null,
                position: null,
                grade: null,
                hiring_date: null,
                contract_end_date: null,
                type_of_contract: null,
                allowed_leave_days: 30, // default to 30
                department_id: null
            }),
            profileImage: null, // profile image

            collaboratorName: null,
        }
    },
    computed: {
        collaboratorId() {
            if(this.role === 'edit') return this.$route.params.id;
            return null;
        }
    },
    mounted() {
        axios.get('/departments').then(response => {
            this.departments = response.data;
        });
        // editing case
        if(this.collaboratorId) {
            axios.get(`/collaborators/${this.collaboratorId}`).then(response => {
                this.collaboratorName = response.data.name;
                Object.assign(response.data, { // password field is hidden on laravel by default, the returned object will not contain password and the input (v-model="mainForm.password") will not link to anything
                    'password': ''
                });
                this.mainForm = new Form(response.data);
            });
        }
    },
    methods: {
        onSubmit() {
            let requestType = this.collaboratorId ? 'put' : 'post';
            let url = this.collaboratorId ? `/collaborators/${this.collaboratorId}` : '/collaborators/create'
            this.mainForm.submit(requestType, url).then(response => {
                let collaborator_id = this.collaboratorId || response.collaborator_id;
                let sectionsRefs = ['leavesSection', 'skillsSection', 'trainingsSection', 'evaluationsSection'];
                
                sectionsRefs.forEach(sectionRef => {
                    this.$refs[sectionRef].submit(collaborator_id);
                })

                if(this.profileImage !== null) {
                    let temp = new FormData(); // temporary variable to handle profile image submission
                    temp.append('profile_image', this.profileImage);
                    axios.post(`/users/${collaborator_id}/profile-image`, temp).then(response => {
                        console.log(response);
                    }).catch(error => console.log(error.response));
                }
            }).then(() => {
                let redirectTo = this.collaboratorId ? { name: 'profile', params: { name: this.collaboratorId } } : { name: 'collaborators' }
                this.$router.replace(redirectTo);
            }).catch();
        },
        deleteCollaborator() {
            if(confirm('Are you sure you want to delete this collaborator?')) {
                axios.delete(`/collaborators/${this.collaboratorId}`).then(() => {
                    this.$router.replace({ name: 'collaborators' });
                }).catch(error => {
                    console.log(error.response);
                })
            }
        },
        onImageChange(event) {
            this.profileImage = event.target.files[0];
        }
    }
}
</script>

<style lang="scss">
    .custom-grid-container {
        display: grid;
        grid-template-columns: 275px minmax(700px, 1fr);
    }
    .list-group {
        background-color: var(--dark);
        a {
            transition: .5s;
            color: rgba(255,255,255,.75);
            border: none;
            &, &:hover {
                background-color: transparent;
            }
            &:hover, &.active {
                color: white;
            }
            &:hover {
                background-color: rgba(0,0,0,.175);
                padding-left: 2em;
            }
            &.active {
                background-color: rgba(0,0,0,.35);
                padding-left: 1.5em;
            }
            &, &.active {
                border-bottom: 1px solid rgba(255,255,255,.2);
            }
            i {
                color: var(--warning);
                margin-right: 1em;
            }
        }
    }
    @media (max-width: 1455px) {
        .custom-grid-container {
            grid-template-columns: 1fr;
            .list-group {
                // max-width: 375px;
                // margin-left: auto;
                // margin-right: auto;
                margin-bottom: .5em;
            }
        }
    }
    .bg-dark a i {
        color: var(--warning);
    }
</style>