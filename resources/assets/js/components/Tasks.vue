<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">My Tasks</div>

                    <div class="panel-body">
                        <div class="input-group">
                            <input  type="text" class="form-control" v-model = "task.name" @keydown.enter="create">
                            <span class="input-group-btn">
                                <button class="btn btn-success" @click="create">Add</button>
                            </span>
                        </div>
                        <div class="tasks-list">
                            <div class="alert alert-danger" v-if="!tasks.length">
                                You have no task!
                            </div>
                            <ul class="list-unstyled">
                                <li v-for="task in tasks" :key="task.id" @click="done(task)" :class="{ done: task.completed}">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" v-model="task.completed">{{ task.name }}
                                        </label>
                                        <div class="pull-right">
                                            <a href="#" @click.prevent="remove(task)"> X </a>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>

                    <div class="panel-footer" v-if="tasks.length">
                        <span class="label label-default"> You have {{ tasks.length}} tasks</span>
                        <span class="label label-success"> {{ completedTasks() }} tasks completed</span>
                        <span class="label label-warning"> {{ remainingTasks() }} tasks left</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.fetchData()
        },
        data () {
            return {
                tasks: [],
                task: {
                    name: ''
                }
            }
        },
        methods: {
            fetchData () {
                axios.get('/api/tasks')
                    .then((res) => {
                        this.tasks = res.data
                    })
                    .catch((err) => {
                        console.log(err)
                    })
            },
            create() {
                if(this.task.name == ''){
                    alert('请先输入任务名');
                    return false;
                }
                axios.post('/api/tasks',this.task)
                    .then((res) => {
                        this.tasks.unshift(res.data);
                        this.task.name = '';
                    })
                    .catch((err) => {
                        console.log(err)
                    })
            },
            done(task){
                axios.put('/api/tasks/' + task.id,{
                    completed: !task.completed
                }).then((res) => {
                    console.log('updated')
                }).catch((err) => {
                    console.log(err)
                })
            },
            remove(task){
                axios.delete('/api/tasks/'+task.id)
                    .then((res) => {
                        const taskIndex = this.tasks.indexOf(task);
                        this.tasks.splice(taskIndex,1)
                    })
                    .catch((err) => {
                        console.log(err)
                    })
            },
            remainingTasks() {
                return this.tasks.filter(task => {
                    return !task.completed
                }).length
            },
            completedTasks() {
                return this.tasks.filter(task => {
                    return task.completed
                }).length
            }
        }
    }
</script>

<style>
    body, .tasks-list {
        padding-top: 20px;
    }
    .done label {
        text-decoration: line-through;
    }
</style>