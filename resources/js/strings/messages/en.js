const messages={
    min_value: (field, num) => field 
                                +' cannot be empty! ' 
                                + num 
                                + (num==1 ? ' item' : ' items') 
                                + (num==1 ? ' is' : ' are')
                                + ' required.',
    };
export default messages;