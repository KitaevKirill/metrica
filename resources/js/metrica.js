var from = document.getElementById('from');
var to = document.getElementById('to');
var fromMoreThenTo = (from.value > to.value);




var mtr = new Vue({
    el: '#mtr',
    data: {
        message: 'Vue в данный момент работает! (Чудеса)',
        modelFrom: from.value,
        modelTo: to.value,
        fromMoreThenTo: fromMoreThenTo
    },
    methods: {
        changeDate: function () {
            this.fromMoreThenTo = (this.modelFrom > this.modelTo);
        }
    }
});