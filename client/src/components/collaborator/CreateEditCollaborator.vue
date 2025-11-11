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
                <ContactForm
                    :form="mainForm"
                    :is-editing="role === 'edit'"
                    @image-change="onImageChange"
                />
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
                                    <h4 class="mb-3">Personal Informations</h4>
                                    <PersonalInfoForm :form="mainForm" />
                                </div>
                                <div class="tab-pane fade" id="list-contract" role="tabpanel" aria-labelledby="list-contract-list">
                                    <h4 class="mb-3">Contractual information</h4>
                                    <EmploymentContractForm :form="mainForm" :departments="departments" />
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
import { LEAVE_TYPES, EVALUATION_TYPES, EVALUATION_STATUSES } from '@/constants/dropdowns';
import ContactForm from './ContactForm.vue';
import PersonalInfoForm from './PersonalInfoForm.vue';
import EmploymentContractForm from './EmploymentContractForm.vue';

export default {
    props: {
        role: null
    },
    components: {
        ContactForm,
        PersonalInfoForm,
        EmploymentContractForm,
        Table: () => {
            return import('@/components/collaborator/CreateEditViewTable');
        },
    },
    data() {
        return {
            departments: [],

            // Dropdown options for nested forms
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