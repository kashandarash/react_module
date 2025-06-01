import { gql } from 'graphql-request';
import {Todo} from "../components/Item";

// Constants for the delete query.
export const DELETE_TODO_ITEM = gql`
    mutation DeleteTodoItem($data: String!) {
        DeleteTodoItem(data: $data)
    }
`;

// Constants for the create query.
export const CREATE_TODO_ITEM = gql`
    mutation CreateTodoItem($data: String!) {
        CreateTodoItem(data: { title: $data }) {
            errors
            item {
                id
                title
                completed
            }
        }
    }
`;

// Constants for the update query.
export const UPDATE_TODO_ITEM = gql`
    mutation UpdateTodoItem($id: String!, $title: String!, $completed: Boolean!) {
        UpdateTodoItem(data: { id: $id, title: $title, completed: $completed}) {
            errors
            item {
                id
                title
                completed
            }
        }
    }
`;

// Object that we expect in response.
export type TodoItemResponse = {
    CreateTodoItem: {
        errors: []
        item: Todo
    };
};
