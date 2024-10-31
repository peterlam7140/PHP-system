const app_dashboard = {
    data() {
        return {
            itemlist_loading: false,
            selectedCourseCode: null,
            courseObj: {},
            courseRecord: []
        }
    },
    watch: {

    },
    methods: {
        initData() {

        },
        async ajaxRecord() {
            let formatScoreRecord = this.formatScoreRecord
            let setItemlistLoading = this.setItemlistLoading
            let itemlist_loading = this.itemlist_loading
            let selectedCourseCode = this.selectedCourseCode

            if(!itemlist_loading){
                setItemlistLoading(true);

                this.event_id = '';

                setTimeout(() => {
                    jQuery.ajax({
                        url: './api/statistic.php?year='+((selectedCourseCode!=null)?selectedCourseCode:''),
                        dataType: "json",
                        crossDomain: true,
                        format: "json",
                        async: false,
                        success: function(result){
                            if(result['ststus'] == true){
                                formatScoreRecord(result); 
                                setItemlistLoading(false);

                            } else {
                                setItemlistLoading(false);
                            }
                        },
                        error: function (request, status, error) {
                            setItemlistLoading(false);
                        }
                    });
    
                }, 100)

            }
        },
        formatScoreRecord(response) {
            this.courseRecord = response['data'];
            this.updateChart()
        },
        loadCourse(courssCode) {
            this.selectedCourseCode = courssCode;
            this.ajaxRecord()
        },
        setItemlistLoading(status) {
            this.itemlist_loading = (status == true);
        },
        updateChart() {
            let scatterTempData = []
            let barTempData = []

            if(this.courseRecord != null && Array.isArray(this.courseRecord)){
                for(let row in this.courseRecord) {
                    if(this.courseRecord[row].score != null) {
                        scatterTempData.push (
                            {
                                label: this.courseRecord[row].code,
                                data: [{
                                    x: this.courseRecord[row].score,
                                    y: this.courseRecord[row].score,
                                }],
                            }
                        )
                    }
                }
            }

            if(this.courseRecord != null && Array.isArray(this.courseRecord)){
                for(let row in this.courseRecord) {
                    if(this.courseRecord[row].score != null) {
                        barTempData.push({
                            label: this.courseRecord[row].code,
                            data: [this.courseRecord[row].score],
                            borderWidth: 1
                        })
                    }
                }
            }

            $('#barCanvas').remove();
            $('#barCanvasContainer').append('<canvas id="barCanvas"></canvas>');
            let bar = document.getElementById('barCanvas')
            this.barChart = new Chart(bar, this.setBarChart(barTempData));

            $('#scatterCanvas').remove();
            $('#scatterCanvasContainer').append('<canvas id="scatterCanvas"></canvas>');
            let scatter = document.getElementById('scatterCanvas')
            this.scatterChart = new Chart(scatter, this.setScatterChart(scatterTempData));
        },
        setBarChart(tempData) {
            return {
                type: 'bar',
                data: {
                labels: ['Score'],
                datasets: tempData
                },
                options: {
                    scales: {
                        y: {
                            max: 100,
                            min: 0
                        }
                    }
                }
            }
        },
        setScatterChart(tempData) {
            return {
                type: 'scatter',
                data: {
                    datasets: tempData,
                },
                options: {
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'bottom',
                            max: 100,
                            min: 0
                        },
                        y: {
                            max: 100,
                            min: 0
                        }
                    }
                }
            }
        }

    },
    async mounted() {
        this.ajaxRecord()
    },
    async created() {
        jQuery('#app-dashboard').show();
        await this.initData()
    }
};