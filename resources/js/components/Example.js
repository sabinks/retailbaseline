import React, { Component } from 'react';

export default class Example extends Component {
    render() {
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-header">Dynamic Form Component</div>

                            <div className="card-body"> {this.props.hello}, Lets create form using frag and drop!</div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}


