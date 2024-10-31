function deleteRecord(tableName, recordId){
    if(confirm('Is delete this record?')){
        let data = {
            'tableName': tableName, 
            'recordId': recordId
        };

        $.ajax({
            url: "./api/deleteRecord.php", 
            data: data,
            type: 'POST',
            success: function(result){
                location.reload();
            },
            error: function(){
                alert('Error')
            }
        });
    }
}