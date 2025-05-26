import { gql } from 'graphql-request';
import {Todo} from "../components/Item";

export const DELETE_TODO_ITEM = gql`
    mutation DeleteTodoItem($data: String!) {
        DeleteTodoItem(data: $data)
    }
`;

export type TodoItemResponse = {
    CreateTodoItem: {
        errors: []
        item: Todo
    };
};

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
